<?php

namespace App\Http\Controllers;

use App\Services\BusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected BusService $busService)
    {
        //
    }

    public function index(Request $request)
    {
        $filterTravelDate = $request->filterTravelDate ? Carbon::parse($request->filterTravelDate) : null;
        $filterDestinationLocation = $request->filterDestinationLocation;
        $filterBus = $request->filterBus ?? '';

        $cacheKey = "buses_{$filterTravelDate}_{$filterDestinationLocation}_{$filterBus}";

        $buses = Cache::rememberForever($cacheKey, function () use ($filterTravelDate, $filterDestinationLocation, $filterBus) {
            return $this->busService->getFilteredBuses($filterTravelDate, $filterDestinationLocation, $filterBus);
        });

        $destinations = Cache::rememberForever('destinations', function () {
            return $this->busService->getDestinations();
        });

        $canLogin = Route::has('login');
        $canRegister = Route::has('register');

        return inertia('Welcome', compact('destinations', 'buses', 'canLogin', 'canRegister'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
