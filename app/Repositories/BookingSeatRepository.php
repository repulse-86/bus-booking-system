<?php

namespace App\Repositories;

use App\Interfaces\BookingSeatInterface;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Database\Eloquent\Collection;

/**
 * repository layer for booking seat
 *
 * @package default
 * @author
 **/
class BookingSeatRepository implements BookingSeatInterface
{
	public function getBookingSeats(Booking $booking): Collection
	{
		return BookingSeat::whereBelongsTo($booking)->get();
	}

	public function store(array $data): BookingSeat
	{
		return BookingSeat::create($data);
	}
} // END class BookingSeatRepository