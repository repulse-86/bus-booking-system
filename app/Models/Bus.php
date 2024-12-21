<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_type',
        'departure_location',
        'destination_location',
        'time_available_start',
        'time_available_end',
        'price_per_ticket',
        'available_seats',
    ];

    /*public function bookings()
    {
        return $this->hasMany(Booking::class);
    }*/
}
