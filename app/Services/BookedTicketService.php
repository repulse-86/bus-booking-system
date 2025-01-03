<?php

namespace App\Services;

use App\Events\TicketBooked;
use App\Models\BookedTicket;
use App\Models\User;
use App\Notifications\BookingPendingApprovalNotification;
use App\Notifications\BookingStatusNotification;
use App\Repositories\BookedTicketRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BookedTicketService
{
    public function __construct(protected BookedTicketRepository $bookedTicketRepository, protected BusService $busService) {}

    public function createBookedTicket(array $data): BookedTicket
    {
        $data['payment_image'] = strtoupper(Str::random(6)).'_'.now()->format('YmdHis');
        $bus = $this->busService->find($data['bus_id']);
        auth()->user()->notify(new BookingPendingApprovalNotification($bus));

        return $this->bookedTicketRepository->create($data);
    }

    public function storeImage(BookedTicket $bookedTicket, $image): void
    {
        TicketBooked::dispatch($bookedTicket, $image);
    }

    public function getBookedTicketsByCustomer(?string $filterId, ?string $filterStatus, string $customerId): LengthAwarePaginator
    {
        return $this->bookedTicketRepository->getBookedTicketsByCustomer($filterId, $filterStatus, $customerId);
    }

    public function getBookedTickets(string $status, ?string $filterId, ?string $filterCustomerName)
    {
        return $this->bookedTicketRepository->getBookedTickets($status, $filterId, $filterCustomerName);
    }

    public function find(string $id): BookedTicket
    {
        return $this->bookedTicketRepository->find($id);
    }

    public function updateStatus(BookedTicket $bookedTicket, string $status)
    {
        $user = User::findOrFail($bookedTicket->customer_id);
        $bus = $this->busService->find($bookedTicket->bus_id);
        $this->bookedTicketRepository->updateStatus($bookedTicket, $status);

        $user->notify(new BookingStatusNotification($bookedTicket, $bus, $status));
    }

    public function getBookingsCountByStatus(string $status): int
    {
        return $this->bookedTicketRepository->getBookingsCountByStatus($status);
    }

    public function getCumulativeSalesPerMonth(): Collection
    {
        return $this->bookedTicketRepository->getCumulativeSalesPerMonth();
    }

    public function getCumulativeBookingsCountPerMonth(): Collection
    {
        return $this->bookedTicketRepository->getCumulativeBookingsCountPerMonth();
    }
}
