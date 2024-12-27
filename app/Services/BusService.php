<?php

namespace App\Services;

use App\Models\Bus;
use App\Repositories\BusRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BusService
{
    public function __construct(protected BusRepository $busRepository)
    {
        //
    }

    /**
     * Get filtered buses based on request parameters.
     */
    public function getFilteredBuses(?Carbon $filterTravelDate, ?string $filterDestinationLocation, ?string $filterBus): Collection
    {
        $busesToRetrieve = [];

        if ($filterTravelDate) {
            $weekOfMonth = $filterTravelDate->weekOfMonth;
            $busesToRetrieve = ($weekOfMonth % 2 == 1) ? range(1, 10) : range(11, 17);
        }

        return $this->busRepository->getBuses($filterTravelDate, $filterDestinationLocation, $filterBus, $busesToRetrieve);
    }

    /**
     * Get a list of distinct bus destinations.
     */
    public function getDestinations(): Collection
    {
        return $this->busRepository->getDestinations();
    }

    public function find(string $id): Bus {
        return $this->busRepository->find($id);
    }

    public function decrementAvailableSeats(string $id) {
        $bus = $this->find($id);

        if ($bus->available_seats > 0) {
            $bus->available_seats--;
            $bus->save();
        } else {
            throw new \Exception('No available seats');
        }
    }
}
