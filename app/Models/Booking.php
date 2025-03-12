<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookedTicketFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'bus_id',
        'travel_date',
        'status',
        'reason_declined',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(BookingSeat::class);
    }
}
