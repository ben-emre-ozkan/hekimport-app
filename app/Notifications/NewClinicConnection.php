<?php

namespace App\Notifications;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewClinicConnection extends Notification implements ShouldQueue
{
    use Queueable;

    protected $clinic;
    protected $user;

    public function __construct(Clinic $clinic, User $user)
    {
        $this->clinic = $clinic;
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Clinic Connection Request')
            ->greeting('Hello ' . $notifiable->name)
            ->line('A new doctor has requested to connect with your clinic:')
            ->line('Doctor: ' . $this->user->name)
            ->line('Clinic: ' . $this->clinic->name)
            ->action('View Request', route('clinics.show', $this->clinic))
            ->line('Please review and take appropriate action.');
    }

    public function toArray($notifiable): array
    {
        return [
            'clinic_id' => $this->clinic->id,
            'clinic_name' => $this->clinic->name,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'type' => 'new_connection',
        ];
    }
} 