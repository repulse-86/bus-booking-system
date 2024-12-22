<?php

namespace App\Repositories;

use App\Interfaces\BusRepositoryInterface;
use App\Models\Bus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 
 */
class BusRepository implements BusRepositoryInterface
{
	public function getBuses(?Carbon $filterTravelDate, ?string $filterDestinationLocation, ?string $filterBus, array $busesToRetrieve)
	{
		return Bus::when($filterDestinationLocation, function ($query) use ($filterDestinationLocation) {
            $query->where('destination_location', 'like', '%' . $filterDestinationLocation . '%');
        })
        ->when($filterTravelDate, function ($query) use ($busesToRetrieve) {
            $query->whereIn(DB::raw('CAST(SUBSTRING(bus_type, -2) AS UNSIGNED)'), $busesToRetrieve);
        })
        ->when($filterBus, function ($query) use ($filterBus) {
            $query->where('bus_type', 'like', '%' . $filterBus . '%');
        })
        ->orderBy('bus_type')
        ->get();
	}
}