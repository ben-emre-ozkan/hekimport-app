<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PersonnelInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have been invited to join ' . config('app.name') . ' as a ' . $notifiable->role . '.')
            ->line('Please click the button below to set up your account and complete the onboarding process.')
            ->action('Accept Invitation', route('invitation.accept', $this->token))
            ->line('This invitation will expire in 7 days.')
            ->line('If you did not request this invitation, please ignore this email.');
    }
} 