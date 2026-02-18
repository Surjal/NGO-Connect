<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NgoRegistrationApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('NGO Registration Approved')
            ->line("Your NGO, {$notifiable->name}, has been approved! You can now access NGO features.")
            ->action('View NGO Dashboard', url('/ngo/profile'))
            ->line('Thank you for joining our platform!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Your NGO, {$notifiable->name}, registration has been approved.",
        ];
    }
}
