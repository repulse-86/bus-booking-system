<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingSeatRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * service layer for booking seat
 *
 * @package default
 * @author
 **/
class BookingSeatService
{
	public function __construct(protected BookingSeatRepository $bookingSeatRepository)
	{

	}

	public function getBookingSeats(Booking $booking): Collection
	{
		return $this->bookingSeatRepository->getBookingSeats($booking);
	}

	public function store(array $seats, Booking $booking): array
	{
		foreach ($seats as $seat) {
            $this->bookingSeatRepository->store([
                'booking_id' => $booking->id,
                'seat' => $seat,
            ]);
        }

        return $seats;
	}
} // END class BookingSeatService