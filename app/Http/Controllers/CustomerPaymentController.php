<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerPaymentController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
    ) {}

    public function store(Request $request)
    {
        if ($request->hasFile('payment_image')) {
            $fileName = $this->bookingService->storeImage($request->file('payment_image'));

            return $fileName;
        }

        return '';
    }

    public function destroy(string $paymentReceipt)
    {
        $filePath = "payments/{$paymentReceipt}";

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
