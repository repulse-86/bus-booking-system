<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\CustomerBookingHistory;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\HomeController;
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
        Route::get('home', [CustomerDashboardController::class, 'index'])->name('index');
        Route::get('bookings/create/{bus}', [CustomerBookingController::class, 'create'])->name('bookings.create');
        Route::resource('bookings', CustomerBookingController::class)->except('create');
        Route::get('history', [CustomerBookingHistory::class, 'index'])->name('booking.history.index');
        Route::post('payment-receipt-upload', [CustomerPaymentController::class, 'store'])->name('payment.store');
        Route::delete('payment-receipt-revert/{paymentReceipt}', [CustomerPaymentController::class, 'destroy'])->name('payment.destroy');
        Route::get('taken-seats', [SeatController::class, 'getSeatsTakenForDateAndBus'])->name('taken-seats');
    });

    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => 'admin',
    ], function () {
        Route::get('home', [AdminDashboardController::class, 'index'])->name('index');
        Route::get('bookings/{status}', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::put('bookings/{booking}/{status}/{reason?}', [AdminBookingController::class, 'update'])->name('bookings.update');
        Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    });
});
