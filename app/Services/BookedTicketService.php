<?php

namespace App\Services;

use App\Models\BookedTicket;
use App\Repositories\BookedTicketRepository;

/**
 * 
 */
class BookedTicketService
{
	public function __construct(protected BookedTicketRepository $bookedTicketRepository)
	{
		
	}

	public function createBookedTicket(array $data): BookedTicket {
		return $this->bookedTicketRepository->create($data);
	}
}