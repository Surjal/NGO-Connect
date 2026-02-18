<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NgoRegistrationPending extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('NGO Registration Pending')
            ->line("Your NGO, {$notifiable->name}, registration is pending admin approval.")
            ->action('View Profile', url('/profile'))
            ->line('Please verify your email address if you haven’t already.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Your NGO, {$notifiable->name}, registration is pending admin approval.",
        ];
    }
}
