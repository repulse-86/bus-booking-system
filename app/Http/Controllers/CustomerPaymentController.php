<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use Illuminate\Http\Request;

class CustomerPaymentController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
    ) {}

    public function store(Request $request): string
    {
        if ($request->hasFile('payment_image')) {
            $fileName = $this->bookingService->storeImage($request->file('payment_image'));

            return $fileName;
        }

        return '';
    }

    public function destroy(string $paymentReceipt): void
    {
        $this->bookingService->deleteImage($paymentReceipt);
    }
}
