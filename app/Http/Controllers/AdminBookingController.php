<?php

namespace App\Http\Controllers;

use App\Events\BookingStatusUpdate;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminBookingController extends Controller
{
    public function __construct(public BookingService $bookingService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function viewPendingBookings(Request $request)
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getBookings('pending', $request->filterId, $request->filterCustomerName);

        return inertia('Admin/PendingBookings', compact('bookings'));
    }

    public function viewApprovedBookings(Request $request)
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getBookings('approved', $request->filterId, $request->filterCustomerName);

        return inertia('Admin/ApprovedBookings', compact('bookings'));
    }

    public function viewDeclinedBookings(Request $request)
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getBookings('declined', $request->filterId, $request->filterCustomerName);

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
        $booking = $this->bookingService->find($id);

        Gate::authorize('view', $booking);

        return inertia('Admin/Booking', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        Gate::authorize('update', $booking);

        $status = $request->status;

        $this->bookingService->updateBookingStatus($booking, $status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
