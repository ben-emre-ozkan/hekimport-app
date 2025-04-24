<?php

namespace App\Notifications;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClinicConnectionRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $clinic;

    public function __construct(Clinic $clinic)
    {
        $this->clinic = $clinic;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Clinic Connection Request Rejected')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your request to connect with ' . $this->clinic->name . ' has been rejected.')
            ->line('If you believe this is an error, please contact the clinic administrator.')
            ->action('Contact Clinic', route('clinics.show', $this->clinic))
            ->line('Thank you for your interest.');
    }

    public function toArray($notifiable): array
    {
        return [
            'clinic_id' => $this->clinic->id,
            'clinic_name' => $this->clinic->name,
            'type' => 'connection_rejected',
        ];
    }
} 