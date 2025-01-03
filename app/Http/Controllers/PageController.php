<?php

namespace App\Http\Controllers;

use App\Services\BookedTicketService;
use App\Services\BusService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(protected BusService $busService, protected UserService $userService, protected BookedTicketService $bookedTicketService) {}

    public function customerIndex(Request $request)
    {
        $filterTravelDate = $request->filterTravelDate ? Carbon::parse($request->filterTravelDate) : null;
        $filterDestinationLocation = $request->filterDestinationLocation;
        $filterBus = $request->filterBus ?? '';

        $buses = $this->busService->getFilteredBuses($filterTravelDate, $filterDestinationLocation, $filterBus);

        $destinations = $this->busService->getDestinations();

        return inertia('Customer/Home', compact('buses', 'destinations'));
    }

    public function adminIndex()
    {
        $usersData = $this->userService->getCurrentWeekUsers();
        $bookingPerBusTypeData = $this->busService->getBookingPerBusTypeData();
        $registeredUserCount = $this->userService->getRegisteredUserCount();
        $pendingBookingsCount = $this->bookedTicketService->getBookingsCountByStatus('pending');
        $approvedBookingsCount = $this->bookedTicketService->getBookingsCountByStatus('approved');
        $declinedBookingsCount = $this->bookedTicketService->getBookingsCountByStatus('declined');
        $cumulativeUsersCountPerMonth = $this->userService->getCumulativeUsersCountPerMonth();
        $cumulativeSalesPerMonth = $this->bookedTicketService->getCumulativeSalesPerMonth();
        $cumulativeBookingsCountPerMonth = $this->bookedTicketService->getCumulativeBookingsCountPerMonth();

        return inertia('Admin/Home', compact('usersData', 'bookingPerBusTypeData', 'registeredUserCount', 'pendingBookingsCount', 'approvedBookingsCount', 'declinedBookingsCount', 'cumulativeSalesPerMonth', 'cumulativeUsersCountPerMonth', 'cumulativeBookingsCountPerMonth'));
    }
}
