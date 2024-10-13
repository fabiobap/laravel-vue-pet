<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Appointment $appointment)
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
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Your appointment was confirmed!')
            ->line('Dear ' . $this->appointment->animal->client->name . ',')
            ->line('Your appointment with ' . $this->appointment->animal->name . ' has been confirmed.')
            ->line('Appointment details:')
            ->line('Vet: ' . $this->appointment->user->name)
            ->line('Date: ' . $this->appointment->appointment_date->format('d/m/Y'))
            ->line('Time: ' . $this->appointment->appointment_time->format('g:i A'))
            ->line('Reason: ' . $this->appointment->symptoms)
            ->line('Thank you for using our application!');
    }
}
