<?php

namespace App\Http\Controllers;

use App\Services\BookingSeatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * seat controller
 *
 * @package default
 * @author
 **/
class SeatController extends Controller
{
	public function __construct(protected BookingSeatService $bookingSeatService) {}

	public function getSeatsTakenForDateAndBus(Request $request): JsonResponse
	{
	    $takenSeats = $this->bookingSeatService->getSeatsTakenForDateAndBus($request->bus_id, $request->travel_date);

	    return response()->json(['taken_seats' => $takenSeats]);
	}
}
