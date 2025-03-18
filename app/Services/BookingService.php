<?php

namespace App\Services;

use App\Events\BookingStatusUpdate;
use App\Events\TicketBooked;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingPendingApprovalNotification;
use App\Notifications\BookingStatusNotification;
use App\Repositories\BookingRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(protected BookingRepository $bookingRepository, protected BusService $busService) {}

    public function createBooking(array $data, int $seats): Booking
    {
        $bus = $this->busService->find($data['bus_id']);
        auth()->user()->notify(new BookingPendingApprovalNotification($bus));

        $data['total_price'] = $bus->price_per_ticket * $seats;

        return $this->bookingRepository->create($data);
    }

    public function storeImage($image): string
    {
        $fileName = strtoupper(Str::random(6)).'_'.now()->format('YmdHis');
        $fileName = basename($fileName).'.png';
        TicketBooked::dispatch($fileName, $image);

        return $fileName;
    }

    public function getBookingsByCustomer(?string $filterId, ?string $filterStatus, string $customerId): LengthAwarePaginator
    {
        return $this->bookingRepository->getBookingsByCustomer($filterId, $filterStatus, $customerId);
    }

    public function getBookings(string $status, ?string $filterId, ?string $filterCustomerName)
    {
        return $this->bookingRepository->getBookings($status, $filterId, $filterCustomerName);
    }

    public function find(string $id): Booking
    {
        return $this->bookingRepository->find($id);
    }

    public function updateBookingStatus(Booking $booking, string $status, ?string $reason)
    {
        $user = User::findOrFail($booking->customer_id);
        $bus = $this->busService->find($booking->bus_id);
        $this->bookingRepository->updateBookingStatus($booking, $status, $reason);

        $user->notify(new BookingStatusNotification($booking, $bus, $status));

        broadcast(new BookingStatusUpdate($booking));
    }

    public function getByStatusBookingCount(string $status): int
    {
        return $this->bookingRepository->getByStatusBookingCount($status);
    }

    public function getCumulativePerMonthSales(): Collection
    {
        return $this->bookingRepository->getCumulativePerMonthSales();
    }

    public function getCumulativePerMonthBookingCount(): Collection
    {
        return $this->bookingRepository->getCumulativePerMonthBookingCount();
    }
}
