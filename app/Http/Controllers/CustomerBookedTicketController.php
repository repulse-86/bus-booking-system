<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookedTicketRequest;
use App\Models\BookedTicket;
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
        $bookings = BookedTicket::with('bus')->where('customer_id', auth()->user()->id)->paginate(10);

        return inertia('Customer/MyBookings', compact('bookings'));
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
        $bookedTicket = BookedTicket::with('bus')->findOrFail($id);

        return inertia('Customer/BookedTicket', compact('bookedTicket'));
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
