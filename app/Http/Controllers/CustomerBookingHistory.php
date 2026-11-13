<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Support\Facades\Gate;
use Inertia\Response;

class CustomerBookingHistory extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getBookingsByCustomer('', 'approved', auth()->user()->id);

        return inertia('Customer/History', [
            'bookings' => inertia()->merge(fn () => $bookings->items()),
            'currentPage' => $bookings->currentPage(),
            'lastPage' => $bookings->lastPage(),
        ]);
    }
}
