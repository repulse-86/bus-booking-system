<?php

namespace App\Notifications;

use App\Models\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingPendingApprovalNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Bus $bus)
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
        return (new MailMessage)
            ->subject('Your Ticket Booking is Pending Approval')
            ->line('We have received your ticket booking request for '.$this->bus->bus_type.' and it has been successfully processed.')
            ->line('Currently, your booking is awaiting approval. You will be notified once it is approved.')
                   // ->action('View Booking Details', url('/bookings'))
            ->line('Thank you for choosing our service! We appreciate your trust in us.');
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
