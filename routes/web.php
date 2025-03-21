<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group([
        'prefix' => 'customer',
        'as' => 'customer.',
        'middleware' => 'customer',
    ], function () {
        Route::get('home', [PageController::class, 'customerIndex'])->name('home');
        Route::get('bookings/create/{bus}', [CustomerBookingController::class, 'create'])->name('bookings.create');
        Route::resource('bookings', CustomerBookingController::class)->except('create');
        Route::get('history', [CustomerBookingController::class, 'viewHistory'])->name('bookings.history');
        Route::post('payment-receipt-upload', [CustomerBookingController::class, 'storePaymentReceipt'])->name('payment-receipt-upload');
        Route::delete('payment-receipt-revert/{paymentReceipt}', [CustomerBookingController::class, 'deletePaymentReceipt'])->name('payment-receipt-revert');
        Route::get('taken-seats', [SeatController::class, 'getSeatsTakenForDateAndBus'])->name('taken-seats');
    });

    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => 'admin',
    ], function () {
        Route::get('home', [PageController::class, 'adminIndex'])->name('home');
        Route::get('pending/bookings', [AdminBookingController::class, 'viewPendingBookings'])->name('bookings.pendingBookings');
        Route::get('approved/bookings', [AdminBookingController::class, 'viewApprovedBookings'])->name('bookings.approvedBookings');
        Route::get('declined/bookings', [AdminBookingController::class, 'viewDeclinedBookings'])->name('bookings.declinedBookings');
        Route::put('bookings/{booking}/{status}/{reason?}', [AdminBookingController::class, 'update'])->name('bookings.update');
        Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    });
});
