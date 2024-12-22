<?php

namespace App\Http\Controllers;

use App\Services\BusService;
use Illuminate\Http\Request;

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
        $buses = $this->busService->getFilteredBuses($request);

        $destinations = $this->busService->getDestinations();

        return inertia('Welcome', compact('destinations', 'buses'));
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
