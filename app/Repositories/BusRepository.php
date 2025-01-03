<?php

namespace App\Repositories;

use App\Interfaces\BusRepositoryInterface;
use App\Models\Bus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BusRepository implements BusRepositoryInterface
{
    public function getBuses(?Carbon $filterTravelDate, ?string $filterDestinationLocation, ?string $filterBus, array $busesToRetrieve): Collection
    {
        return Bus::when($filterDestinationLocation, function ($query) use ($filterDestinationLocation) {
            $query->where('destination_location', 'like', '%'.$filterDestinationLocation.'%');
        })
            ->when($filterTravelDate, function ($query) use ($busesToRetrieve) {
                $query->whereIn(DB::raw('CAST(SUBSTRING(bus_type, -2) AS UNSIGNED)'), $busesToRetrieve);
            })
            ->when($filterBus, function ($query) use ($filterBus) {
                $query->where('bus_type', 'like', '%'.$filterBus.'%');
            })
            ->orderBy('bus_type')
            ->get();
    }

    public function getDestinations(): Collection
    {
        return Bus::distinct()->pluck('destination_location');
    }

    public function find(string $id): Bus
    {
        return Bus::findOrFail($id);
    }

    public function getBookingPerBusTypeData(): Collection
    {
        return DB::table('buses')
        ->join('booked_tickets', 'buses.id', '=', 'booked_tickets.bus_id')
        ->select('buses.bus_type', DB::raw('COUNT(booked_tickets.id) as total_booked_tickets'))
        ->groupBy('buses.bus_type')
        ->orderBy('buses.bus_type', 'asc')
        ->get();
    }
}
