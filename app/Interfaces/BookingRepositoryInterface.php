<?php

namespace App\Interfaces;

use App\Models\Booking;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BookingRepositoryInterface
{
    public function getBookingsByCustomer(?string $filterId, ?string $filterStatus, string $customerId): LengthAwarePaginator;

    public function getBookings(string $status, ?string $filterId, ?string $filterCustomerName): LengthAwarePaginator;

    public function create(array $data): Booking;

    public function find(string $id): Booking;

    public function delete(Booking $booking): void;

    public function updateBookingStatus(Booking $booking, string $status): void;

    public function getByStatusBookingCount(string $status): int;

    public function getCumulativePerMonthSales(): Collection;

    public function getCumulativePerMonthBookingCount(): Collection;
}
