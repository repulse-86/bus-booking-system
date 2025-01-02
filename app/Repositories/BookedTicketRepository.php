<?php

namespace App\Repositories;

use App\Interfaces\BookedTicketRepositoryInterface;
use App\Models\BookedTicket;
use Illuminate\Pagination\LengthAwarePaginator;

class BookedTicketRepository implements BookedTicketRepositoryInterface
{
    public function getBookedTicketsByCustomer(?string $filterId, ?string $filterStatus, string $customerId): LengthAwarePaginator
    {
        return BookedTicket::with('bus')
            ->when($filterId, function ($query) use ($filterId) {
                $query->where('id', $filterId);
            })
            ->when($filterStatus, function ($query) use ($filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(10);
    }

    public function getBookedTickets(string $status, ?string $filterId, ?string $filterCustomerName): LengthAwarePaginator
    {
        return BookedTicket::with('bus', 'customer')
            ->when($filterId, function ($query) use ($filterId) {
                $query->where('id', '=', $filterId);
            })
            ->when($filterCustomerName, function ($query) use ($filterCustomerName) {
                $query->whereHas('customer', function ($query) use ($filterCustomerName) {
                    $query->where('name', 'like', '%'.$filterCustomerName.'%');
                });
            })
            ->where('status', $status)
            ->paginate(10);
    }

    public function create(array $data): BookedTicket
    {
        return BookedTicket::create($data);
    }

    public function find(string $id): BookedTicket
    {
        return BookedTicket::with(['bus', 'customer'])->findOrFail($id);
    }

    public function delete(BookedTicket $bookedTicket): void
    {
        $bookedTicket->delete();
    }

    public function updateStatus(BookedTicket $bookedTicket, string $status): void
    {
        $bookedTicket->status = $status;
        $bookedTicket->save();
    }
}
