<?php

namespace App\Repositories;

use App\Interfaces\BookedTicketRepositoryInterface;
use App\Models\BookedTicket;

class BookedTicketRepository implements BookedTicketRepositoryInterface
{
    public function getAll(): array
    {
        return BookedTicket::all()->toArray();
    }

    public function create(array $data): BookedTicket
    {
        return BookedTicket::create($data);
    }

    public function find(BookedTicket $bookedTicket): BookedTicket
    {
        return $bookedTicket;
    }

    public function delete(BookedTicket $bookedTicket): void
    {
        $bookedTicket->delete();
    }
}
