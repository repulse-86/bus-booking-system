<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Bus;
use App\Services\BookingSeatService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CustomerBookingController extends Controller
{
    public function __construct(protected BookingService $bookingService, protected BookingSeatService $bookingSeatService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getBookingsByCustomer((string) $request->filterId, $request->filterStatus, auth()->user()->id);

        return inertia('Customer/MyBookings', compact('bookings'));
    }

    /**
     * Display a listing of the booking history approved only.
     */
    public function viewHistory()
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getBookingsByCustomer('', 'approved', auth()->user()->id);

        return inertia('Customer/History', [
            'bookings' => inertia()->merge(fn () => $bookings->items()),
            'currentPage' => $bookings->currentPage(),
            'lastPage' => $bookings->lastPage(),
        ]);
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

        $booking = $this->bookingService->createBooking($request->validated());

        $this->bookingSeatService->store($request->seats, $booking);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = $this->bookingService->find($id);

        Gate::authorize('view', $booking);

        $seats = $this->bookingSeatService->getBookingSeats($booking);

        return inertia('Customer/Booking', compact('booking', 'seats'));
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

    public function storePaymentReceipt(Request $request)
    {
        if ($request->hasFile('payment_image')) {
            $fileName = $this->bookingService->storeImage($request->file('payment_image'));

            return $fileName;
        }

        return '';
    }

    public function deletePaymentReceipt(string $paymentReceipt)
    {
        $filePath = "payments/{$paymentReceipt}";

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
