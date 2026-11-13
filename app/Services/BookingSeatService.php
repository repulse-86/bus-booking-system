<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingSeatRepository;
use App\Repositories\BusRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * service layer for booking seat
 *
 * @package default
 * @author
 **/
class BookingSeatService
{
	public function __construct(
		protected BookingSeatRepository $bookingSeatRepository,
		protected BusRepository $busRepository
	) {}

	public function getBookingSeats(Booking $booking): Collection
	{
		return $this->bookingSeatRepository->getBookingSeats($booking);
	}

	public function getSeatsTakenForDateAndBus(string $busId, string $travelDate): SupportCollection
	{
		$this->busRepository->find($busId);

		return $this->bookingSeatRepository->getSeatsTakenForDateAndBus($busId, $travelDate);
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
}