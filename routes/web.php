<?php

use App\Http\Controllers\BookedTicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
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

    Route::prefix('customer')->group(function () {
        Route::get('home', [PageController::class, 'customerIndex'])->name('customer.home');
        Route::get('book-ticket/create/{bus}', [BookedTicketController::class, 'create'])->name('booked-tickets.create');
        Route::post('book-ticket', [BookedTicketController::class, 'store'])->name('booked-tickets.store');
    });
});
