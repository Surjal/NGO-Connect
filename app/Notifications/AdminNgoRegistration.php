<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class AdminNgoRegistration extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ngo;

    public function __construct(User $ngo)
    {
        $this->ngo = $ngo;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New NGO Registration Pending')
            ->line("A new NGO, {$this->ngo->name}, has registered and is awaiting your approval.")
            ->action('Review NGOs', url('/admin/ngos'))
            ->line('Please review the registration details.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "New NGO registration from {$this->ngo->name} is pending approval.",
            'ngo_id' => $this->ngo->id,
        ];
    }
}
