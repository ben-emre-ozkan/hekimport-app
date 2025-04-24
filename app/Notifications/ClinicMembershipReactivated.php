<?php

namespace App\Notifications;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClinicMembershipReactivated extends Notification implements ShouldQueue
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
            ->subject('Clinic Membership Reactivated')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your membership in ' . $this->clinic->name . ' has been reactivated.')
            ->line('You now have full access to the clinic\'s resources and features.')
            ->action('View Clinic', route('clinics.show', $this->clinic))
            ->line('Welcome back!');
    }

    public function toArray($notifiable): array
    {
        return [
            'clinic_id' => $this->clinic->id,
            'clinic_name' => $this->clinic->name,
            'type' => 'membership_reactivated',
        ];
    }
} 