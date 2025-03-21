<?php

namespace App\Interfaces;

use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Database\Eloquent\Collection;

/**
 * interface for bookingseat repository
 *
 * @package default
 * @author
 **/
interface BookingSeatInterface
{
	public function getBookingSeats(Booking $booking): Collection;
	public function store(array $data): BookingSeat;
} // END interface BookingSeatInterface