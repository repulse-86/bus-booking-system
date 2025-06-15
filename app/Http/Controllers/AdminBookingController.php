<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Repositories\BookingSeatRepository;
use App\Services\BookingSeatService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected BookingSeatService $bookingSeatService,
        protected BookingRepository $bookingRepository,
        protected BookingSeatRepository $bookingSeatRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $status)
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingRepository->getBookings($status, $request->filterId, $request->filterCustomerName);

        return inertia('Admin/PendingBookings', compact('bookings'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = $this->bookingRepository->find($id);

        Gate::authorize('view', $booking);

        $seats = $this->bookingSeatRepository->getBookingSeats($booking);

        return inertia('Admin/Booking', compact('booking', 'seats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        Gate::authorize('update', $booking);

        $status = $request->status;
        $reason = $request->reason;

        $this->bookingService->updateBookingStatus($booking, $status, $reason);
    }
}
