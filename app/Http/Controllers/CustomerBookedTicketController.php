<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookedTicketRequest;
use App\Models\BookedTicket;
use App\Models\Bus;
use App\Services\BookedTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CustomerBookedTicketController extends Controller
{
    public function __construct(public BookedTicketService $bookedTicketService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', BookedTicket::class);

        $bookings = $this->bookedTicketService->getBookedTicketsByCustomer((string) $request->filterId, $request->filterStatus, auth()->user()->id);

        return inertia('Customer/MyBookings', compact('bookings'));
    }

    /**
     * Display a listing of the booking history approved only.
     */
    public function viewHistory()
    {
        Gate::authorize('viewAny', BookedTicket::class);

        $bookings = $this->bookedTicketService->getBookedTicketsByCustomer('', 'approved', auth()->user()->id);

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
        Gate::authorize('create', BookedTicket::class);

        return inertia('Customer/BookingForm', compact('bus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookedTicketRequest $request)
    {
        Gate::authorize('create', BookedTicket::class);

        $this->bookedTicketService->createBookedTicket($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bookedTicket = $this->bookedTicketService->find($id);

        Gate::authorize('view', $bookedTicket);

        return inertia('Customer/BookedTicket', compact('bookedTicket'));
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
            $fileName = $this->bookedTicketService->storeImage($request->file('payment_image'));
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
