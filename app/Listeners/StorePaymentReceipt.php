<?php

namespace App\Listeners;

use App\Events\TicketBooked;
use Illuminate\Queue\SerializesModels;

class StorePaymentReceipt
{
    use SerializesModels;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketBooked $event): void
    {
        $fileName = $event->imageName;
        $image = $event->image;
        $image->storeAs('payments', $fileName, 'public');
    }
}
