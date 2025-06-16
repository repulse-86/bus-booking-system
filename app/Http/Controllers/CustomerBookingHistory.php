<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\Gate;

class CustomerBookingHistory extends Controller
{
    public function __construct(protected BookingRepository $bookingRepository) {}

    public function index()
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingRepository->getBookingsByCustomer('', 'approved', auth()->user()->id);

        return inertia('Customer/History', [
            'bookings' => inertia()->merge(fn () => $bookings->items()),
            'currentPage' => $bookings->currentPage(),
            'lastPage' => $bookings->lastPage(),
        ]);
    }
}
