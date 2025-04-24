<?php

namespace App\Notifications;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClinicConnectionApproved extends Notification implements ShouldQueue
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
            ->subject('Clinic Connection Request Approved')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your request to connect with ' . $this->clinic->name . ' has been approved.')
            ->line('You can now access the clinic\'s resources and collaborate with other members.')
            ->action('View Clinic', route('clinics.show', $this->clinic))
            ->line('Welcome to the team!');
    }

    public function toArray($notifiable): array
    {
        return [
            'clinic_id' => $this->clinic->id,
            'clinic_name' => $this->clinic->name,
            'type' => 'connection_approved',
        ];
    }
} 