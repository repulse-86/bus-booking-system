<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use App\Services\BusService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(protected BusService $busService, protected UserService $userService, protected BookingService $bookingService) {}

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
        $usersData = $this->userService->getCurrentWeekNewUserCount();
        $bookingPerBusTypeData = $this->busService->getBookingsPerBusTypeData();
        $registeredUserCount = $this->userService->getRegisteredUserCount();
        $pendingBookingsCount = $this->bookingService->getByStatusBookingCount('pending');
        $approvedBookingsCount = $this->bookingService->getByStatusBookingCount('approved');
        $declinedBookingsCount = $this->bookingService->getByStatusBookingCount('declined');
        $cumulativePerMonthUserCount = $this->userService->getCumulativePerMonthUserCount();
        $cumulativePerMonthSales = $this->bookingService->getCumulativePerMonthSales();
        $cumulativePerMonthBookingCount = $this->bookingService->getCumulativePerMonthBookingCount();

        return inertia('Admin/Home', compact('usersData', 'bookingPerBusTypeData', 'registeredUserCount', 'pendingBookingsCount', 'approvedBookingsCount', 'declinedBookingsCount', 'cumulativePerMonthSales', 'cumulativePerMonthUserCount', 'cumulativePerMonthBookingCount'));
    }
}
