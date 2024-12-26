<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedTicket extends Model
{
    /** @use HasFactory<\Database\Factories\BookedTicketFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'bus_id',
        'seat',
        'payment_image',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
