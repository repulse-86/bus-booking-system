<?php

namespace App\Interfaces;

use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * interface for bookingseat repository
 *
 * @package default
 * @author
 **/
interface BookingSeatInterface
{
	public function getBookingSeats(Booking $booking): Collection;
	public function getSeatsTakenForDateAndBus(string $busId, string $travelDate): SupportCollection;
	public function store(array $data): BookingSeat;
}