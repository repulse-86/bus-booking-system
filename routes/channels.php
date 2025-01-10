<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('booking.update.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
