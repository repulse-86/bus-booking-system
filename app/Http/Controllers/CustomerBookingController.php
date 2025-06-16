<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Bus;
use App\Repositories\BookingRepository;
use App\Repositories\BookingSeatRepository;
use App\Services\BookingSeatService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerBookingController extends Controller
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
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingRepository->getBookingsByCustomer((string) $request->filterId, $request->filterStatus, auth()->user()->id);

        return inertia('Customer/MyBookings', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Bus $bus)
    {
        Gate::authorize('create', Booking::class);

        return inertia('Customer/BookingForm', compact('bus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        Gate::authorize('create', Booking::class);

        $booking = $this->bookingService->createBooking($request->validated(), count($request->seats));

        $this->bookingSeatService->store($request->seats, $booking);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = $this->bookingRepository->find($id);

        Gate::authorize('view', $booking);

        $seats = $this->bookingSeatRepository->getBookingSeats($booking);

        return inertia('Customer/Booking', compact('booking', 'seats'));
    }
}
