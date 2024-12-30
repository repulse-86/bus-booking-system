<?php

namespace App\Http\Controllers;

use App\Models\BookedTicket;
use App\Services\BookedTicketService;
use Illuminate\Http\Request;

class AdminBookedTicketController extends Controller
{
    public function __construct(public BookedTicketService $bookedTicketService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function pendingBookings(Request $request)
    {
        $bookings = $this->bookedTicketService->getBookedTickets('pending', $request->filterId, $request->filterCustomerName);
        
        return inertia('Admin/PendingBookings', compact('bookings'));
    }

    public function approvedBookings(Request $request)
    {
        $bookings = $this->bookedTicketService->getBookedTickets('approved', $request->filterId, $request->filterCustomerName);
        
        return inertia('Admin/ApprovedBookings', compact('bookings'));
    }

    public function declinedBookings(Request $request)
    {
        $bookings = $this->bookedTicketService->getBookedTickets('declined', $request->filterId, $request->filterCustomerName);
        
        return inertia('Admin/DeclinedBookings', compact('bookings'));
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
    public function update(Request $request, BookedTicket $bookedTicket)
    {
        $status = $request->status;

        $this->bookedTicketService->updateStatus($bookedTicket, $status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
