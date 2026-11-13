<?php

namespace App\Services;

use App\Repositories\BusRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BusService
{
    public function __construct(protected BusRepository $busRepository)
    {
    }

    /**
     * Get filtered buses based on request parameters.
     */
    public function getFilteredBuses(?Carbon $filterTravelDate, ?string $filterDestinationLocation, ?string $filterBus): Collection
    {
        $cacheKey = "buses_{$filterTravelDate}_{$filterDestinationLocation}_{$filterBus}";

        return Cache::rememberForever($cacheKey, function () use ($filterTravelDate, $filterDestinationLocation, $filterBus) {
            $busesToRetrieve = [];

            if ($filterTravelDate) {
                $weekOfMonth = $filterTravelDate->weekOfMonth;
                $busesToRetrieve = ($weekOfMonth % 2 == 1) ? range(1, 10) : range(11, 17);
            }

            return $this->busRepository->getBuses($filterTravelDate, $filterDestinationLocation, $filterBus, $busesToRetrieve);
        });
    }

    /**
     * Get a list of distinct bus destinations.
     */
    public function getDestinations(): Collection
    {
        return Cache::rememberForever('destinations', function () {
            $destinations = $this->busRepository->getDestinations();

            return $destinations->map(function ($destination) {
                return [
                    'label' => $destination,
                    'value' => $destination,
                ];
            });
        });
    }

    public function decrementAvailableSeats(string $id): void
    {
        $bus = $this->busRepository->find($id);

        if ($bus->available_seats > 0) {
            $bus->available_seats--;
            $bus->save();
        } else {
            throw new \Exception('No available seats');
        }
    }
}
