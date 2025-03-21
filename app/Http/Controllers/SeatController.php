<?php

namespace App\Http\Controllers;

use App\Models\BookingSeat;
use App\Models\Bus;
use Illuminate\Http\Request;

/**
 * seat controller
 *
 * @package default
 * @author
 **/
class SeatController extends Controller
{
	public function getSeatsTakenForDateAndBus(Request $request)
	{
	    $bus = Bus::findOrFail($request->bus_id);
	    $takenSeats = BookingSeat::whereHas('booking', function ($query) use ($request, $bus) {
	        $query->whereBelongsTo($bus)
	              ->where('travel_date', $request->travel_date);
	    })->pluck('seat');

	    return response()->json(['taken_seats' => $takenSeats]);
	}
} // END class SeatController