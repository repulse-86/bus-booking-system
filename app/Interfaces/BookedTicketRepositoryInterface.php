<?php

namespace App\Interfaces;

use App\Models\BookedTicket;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookedTicketRepositoryInterface
{
    public function getBookedTicketsByCustomer(?string $filterId, ?string $filterStatus, string $customerId): LengthAwarePaginator;

    public function getPendingBookedTickets(): LengthAwarePaginator;

    public function create(array $data): BookedTicket;

    public function find(string $id): BookedTicket;

    public function delete(BookedTicket $bookedTicket): void;
}
