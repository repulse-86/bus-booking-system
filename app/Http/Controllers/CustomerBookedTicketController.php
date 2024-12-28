<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookedTicketRequest;
use App\Models\Bus;
use App\Services\BookedTicketService;
use Illuminate\Http\Request;

class CustomerBookedTicketController extends Controller
{
    public function __construct(public BookedTicketService $bookedTicketService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Bus $bus)
    {
        return inertia('Customer/BookingForm', compact('bus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookedTicketRequest $request)
    {
        $bookedTicket = $this->bookedTicketService->createBookedTicket($request->validated());

        if ($request->hasFile('payment_image')) {
            $this->bookedTicketService->storeImage($bookedTicket, $request->file('payment_image'));
        }
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
