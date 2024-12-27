<?php

namespace App\Services;

use App\Models\BookedTicket;
use App\Repositories\BookedTicketRepository;

/**
 * 
 */
class BookedTicketService
{
	public function __construct(protected BookedTicketRepository $bookedTicketRepository, protected BusService $busService)
	{
		
	}

	public function createBookedTicket(array $data, string $fileName): BookedTicket {
		$data['payment_image'] = $fileName;
		
		$this->busService->decrementAvailableSeats($data['bus_id']);
		
		return $this->bookedTicketRepository->create($data);
	}

	public function storeImage($image)
    {
        $fileName = now()->format('YmdHis') . '_' . $image->getClientOriginalName();
        
        $image->storeAs('payments', $fileName, 'public');

        return $fileName;
    }
}