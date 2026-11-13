<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Bus;
use App\Services\BookingSeatService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Response;

class CustomerBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected BookingSeatService $bookingSeatService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getBookingsByCustomer((string) $request->filterId, $request->filterStatus, auth()->user()->id);

        return inertia('Customer/MyBookings', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Bus $bus): Response
    {
        Gate::authorize('create', Booking::class);

        return inertia('Customer/BookingForm', compact('bus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): void
    {
        Gate::authorize('create', Booking::class);

        $booking = $this->bookingService->createBooking($request->validated(), count($request->seats));

        $this->bookingSeatService->store($request->seats, $booking);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $booking = $this->bookingService->findBooking($id);

        Gate::authorize('view', $booking);

        $seats = $this->bookingSeatService->getBookingSeats($booking);

        return inertia('Customer/Booking', compact('booking', 'seats'));
    }
}
