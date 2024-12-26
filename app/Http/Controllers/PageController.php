<?php

namespace App\Http\Controllers;

use App\Services\BusService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(protected BusService $busService) {

    }

    public function customerIndex(Request $request) {
        $filterTravelDate = $request->filterTravelDate ? Carbon::parse($request->filterTravelDate) : null;
        $filterDestinationLocation = $request->filterDestinationLocation;
        $filterBus = $request->filterBus ?? '';

        $buses = $this->busService->getFilteredBuses($filterTravelDate, $filterDestinationLocation, $filterBus);

        $destinations = $this->busService->getDestinations();
        return inertia('Customer/Home', compact('buses', 'destinations'));
    }
}
