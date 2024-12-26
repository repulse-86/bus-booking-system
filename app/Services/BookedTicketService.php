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

	public function createBookedTicket(array $data, string $fileName): BookedTicket {
		$data['payment_image'] = $fileName;
		
		return $this->bookedTicketRepository->create($data);
	}

	public function storeImage($image)
    {
        $fileName = now()->format('YmdHis') . '_' . $image->getClientOriginalName();
        
        $image->storeAs('payments', $fileName, 'public');

        return $fileName;
    }
}