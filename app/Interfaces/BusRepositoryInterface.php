<?php

namespace App\Interfaces;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface BusRepositoryInterface
{
    public function getBuses(?Carbon $filterTravelDate, ?string $filterDestinationLocation, ?string $filterBus, array $busesToRetrieve): Collection;

    public function getDestinations(): Collection;

    public function getBookingsPerBusTypeData(): Collection;
}
