<?php

namespace App\Interfaces;

use App\Models\BookedTicket;

/**
 * 
 */
interface BookedTicketRepositoryInterface
{
	public function getAll(): array;
	public function create(array $data): BookedTicket;
	public function find(BookedTicket $bookedTicket): BookedTicket;
	public function delete(BookedTicket $bookedTicket): void;
}