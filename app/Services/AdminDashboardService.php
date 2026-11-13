<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\BusRepository;
use App\Repositories\UserRepository;

class AdminDashboardService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected BookingRepository $bookingRepository,
        protected BusRepository $busRepository,
    ) {}

    /**
     * Get the aggregated data required by the admin dashboard.
     */
    public function getDashboardData(): array
    {
        return [
            'usersData' => $this->userRepository->getCurrentWeekNewUserCount(),
            'bookingPerBusTypeData' => $this->busRepository->getBookingsPerBusTypeData(),
            'registeredUserCount' => $this->userRepository->getRegisteredUserCount(),
            'pendingBookingsCount' => $this->bookingRepository->getByStatusBookingCount('pending'),
            'approvedBookingsCount' => $this->bookingRepository->getByStatusBookingCount('approved'),
            'declinedBookingsCount' => $this->bookingRepository->getByStatusBookingCount('declined'),
            'cumulativePerMonthUserCount' => $this->userRepository->getCumulativePerMonthUserCount(),
            'cumulativePerMonthSales' => $this->bookingRepository->getCumulativePerMonthSales(),
            'cumulativePerMonthBookingCount' => $this->bookingRepository->getCumulativePerMonthBookingCount(),
        ];
    }
}
