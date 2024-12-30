<?php

use App\Http\Controllers\CustomerBookedTicketController;
use App\Http\Controllers\AdminBookedTicketController;
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

    Route::group([
        'prefix' => 'customer',
        'as' => 'customer.',
    ], function () {
        Route::get('home', [PageController::class, 'customerIndex'])->name('home');
        Route::get('booked-tickets/create/{bus}', [CustomerBookedTicketController::class, 'create'])->name('booked-tickets.create');
        Route::resource('booked-tickets', CustomerBookedTicketController::class)->except('create');
    });

    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
    ], function () {
        Route::get('home', [PageController::class, 'adminIndex'])->name('home');
    });
});
