<?php

namespace App\Http\Controllers;

use App\Repositories\BookingRepository;
use App\Repositories\BusRepository;
use App\Repositories\UserRepository;
use App\Services\BusService;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected BusService $busService,
        protected UserRepository $userRepository,
        protected BookingRepository $bookingRepostory,
        protected BusRepository $busRepository
    ) {}

    public function index()
    {
        $usersData = $this->userRepository->getCurrentWeekNewUserCount();
        $bookingPerBusTypeData = $this->busRepository->getBookingsPerBusTypeData();
        $registeredUserCount = $this->userRepository->getRegisteredUserCount();
        $pendingBookingsCount = $this->bookingRepostory->getByStatusBookingCount('pending');
        $approvedBookingsCount = $this->bookingRepostory->getByStatusBookingCount('approved');
        $declinedBookingsCount = $this->bookingRepostory->getByStatusBookingCount('declined');
        $cumulativePerMonthUserCount = $this->userRepository->getCumulativePerMonthUserCount();
        $cumulativePerMonthSales = $this->bookingRepostory->getCumulativePerMonthSales();
        $cumulativePerMonthBookingCount = $this->bookingRepostory->getCumulativePerMonthBookingCount();

        return inertia('Admin/Home', compact('usersData', 'bookingPerBusTypeData', 'registeredUserCount', 'pendingBookingsCount', 'approvedBookingsCount', 'declinedBookingsCount', 'cumulativePerMonthSales', 'cumulativePerMonthUserCount', 'cumulativePerMonthBookingCount'));
    }//
}
