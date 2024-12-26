<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BookedTicket;
use App\Http\Requests\StoreBookedTicketRequest;
use App\Http\Requests\UpdateBookedTicketRequest;
use App\Services\BookedTicketService;

class BookedTicketController extends Controller
{
    public function __construct(protected BookedTicketService $bookedTicketService)
    {
        
    }
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
        return inertia('Customer/SeatSelection', compact('bus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookedTicketRequest $request)
    {
        $this->bookedTicketService->createBookedTicket($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(BookedTicket $bookedTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookedTicket $bookedTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookedTicketRequest $request, BookedTicket $bookedTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookedTicket $bookedTicket)
    {
        //
    }
}
