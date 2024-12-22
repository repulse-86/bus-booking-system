<?php

namespace App\Services;

use App\Models\Bus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BusService
{
    /**
     * Get filtered buses based on request parameters.
     */
    public function getFilteredBuses($request): Collection
    {
        $filterTravelDate = $request->filterTravelDate ? Carbon::parse($request->filterTravelDate) : null;
        $busesToRetrieve = [];

        if ($filterTravelDate) {
            $weekOfMonth = $filterTravelDate->weekOfMonth;
            $busesToRetrieve = ($weekOfMonth % 2 == 1) ? range(1, 10) : range(11, 17);
        }

        return Bus::when($request->filterDestinationLocation, function ($query) use ($request) {
                    $query->where('destination_location', 'like', '%' . $request->filterDestinationLocation . '%');
                })
                ->when($filterTravelDate, function ($query) use ($busesToRetrieve) {
                    $query->whereIn(DB::raw('CAST(SUBSTRING(bus_type, -2) AS UNSIGNED)'), $busesToRetrieve);
                })
                ->when($request->filterBus, function ($query) use ($request) {
                    $query->where('bus_type', 'like', '%' . $request->filterBus . '%');
                })
                ->orderBy('bus_type')
                ->get();
    }

    /**
     * Get a list of distinct bus destinations.
     */
    public function getDestinations(): Collection
    {
        return Bus::distinct()->pluck('destination_location');
    }
}
