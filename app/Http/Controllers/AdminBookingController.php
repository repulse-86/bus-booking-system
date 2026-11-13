<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingSeatService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Response;

class AdminBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected BookingSeatService $bookingSeatService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $status): Response
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getBookings($status, $request->filterId, $request->filterCustomerName);

        return inertia('Admin/PendingBookings', compact('bookings'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $booking = $this->bookingService->findBooking($id);

        Gate::authorize('view', $booking);

        $seats = $this->bookingSeatService->getBookingSeats($booking);

        return inertia('Admin/Booking', compact('booking', 'seats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking): void
    {
        Gate::authorize('update', $booking);

        $status = $request->status;
        $reason = $request->reason;

        $this->bookingService->updateBookingStatus($booking, $status, $reason);
    }
}
