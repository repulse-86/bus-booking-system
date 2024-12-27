<?php

namespace App\Interfaces;

use App\Models\Bus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface BusRepositoryInterface
{
    public function getBuses(?Carbon $filterTravelDate, ?string $filterDestinationLocation, ?string $filterBus, array $busesToRetrieve): Collection;
    public function getDestinations(): Collection;
    public function find(string $id): Bus;
}
