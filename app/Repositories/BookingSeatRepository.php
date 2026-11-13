<?php

namespace App\Repositories;

use App\Interfaces\BookingSeatInterface;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

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

	public function getSeatsTakenForDateAndBus(string $busId, string $travelDate): SupportCollection
	{
		return BookingSeat::whereHas('booking', function ($query) use ($busId, $travelDate) {
			$query->where('bus_id', $busId)
				->where('travel_date', $travelDate);
		})->pluck('seat');
	}

	public function store(array $data): BookingSeat
	{
		return BookingSeat::create($data);
	}
}