<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Booking $booking,
        public Bus $bus,
        public string $status)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $bookingId = $this->booking->id;
        $departureLocation = $this->bus->departure_location;
        $destinationLocation = $this->bus->destination_location;

        return (new MailMessage)
            ->subject('Your Booking is '.$this->status)
            ->line("Your booking #{$bookingId} for the bus from {$departureLocation} to {$destinationLocation} has been {$this->status}.")
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
