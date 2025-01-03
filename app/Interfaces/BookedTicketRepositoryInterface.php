<?php

namespace App\Interfaces;

use App\Models\BookedTicket;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BookedTicketRepositoryInterface
{
    public function getBookedTicketsByCustomer(?string $filterId, ?string $filterStatus, string $customerId): LengthAwarePaginator;

    public function getBookedTickets(string $status, ?string $filterId, ?string $filterCustomerName): LengthAwarePaginator;

    public function create(array $data): BookedTicket;

    public function find(string $id): BookedTicket;

    public function delete(BookedTicket $bookedTicket): void;

    public function updateStatus(BookedTicket $bookedTicket, string $status): void;

    public function getBookingsCountByStatus(string $status): int;

    public function getCumulativeSalesPerMonth(): Collection;

    public function getCumulativeBookingsCountPerMonth(): Collection;
}
