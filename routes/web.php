<?php

use App\Models\Bus;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

Route::get('/', function (Request $request) {
    $filterTravelDate = $request->filterTravelDate ? Carbon::parse($request->filterTravelDate) : null;
    $busesToRetrieve = [];

    if ($filterTravelDate) {
        $weekOfMonth = $filterTravelDate->weekOfMonth;
        $busesToRetrieve = ($weekOfMonth % 2 == 1) ? range(1, 10) : range(11, 17);
    }

    $buses = Bus::when($request->filterDestinationLocation, function ($query) use ($request) {
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

    $destinations = Bus::distinct()->pluck('destination_location');

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'buses' => $buses,
        'destinations' => $destinations,
    ]);
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
