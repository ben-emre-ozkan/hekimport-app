<?php

namespace App\Notifications;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClinicRoleUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $clinic;
    protected $newRole;

    public function __construct(Clinic $clinic, string $newRole)
    {
        $this->clinic = $clinic;
        $this->newRole = $newRole;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Clinic Role Updated')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your role in ' . $this->clinic->name . ' has been updated.')
            ->line('New Role: ' . ucfirst($this->newRole))
            ->action('View Clinic', route('clinics.show', $this->clinic))
            ->line('Your permissions and access have been updated accordingly.');
    }

    public function toArray($notifiable): array
    {
        return [
            'clinic_id' => $this->clinic->id,
            'clinic_name' => $this->clinic->name,
            'new_role' => $this->newRole,
            'type' => 'role_updated',
        ];
    }
} 