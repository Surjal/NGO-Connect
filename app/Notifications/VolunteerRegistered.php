<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class VolunteerRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;
    protected $volunteer;

    public function __construct(Event $event, $volunteer)
    {
        $this->event = $event;
        $this->volunteer = $volunteer;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Volunteer Registered for Your Event')
            ->line("{$this->volunteer->name} has registered as a volunteer for your event: {$this->event->title}.")
            ->action('View Volunteers', url('/ngo/volunteers'))
            ->line('Thank you for organizing impactful events!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->volunteer->name} has registered as a volunteer for your event: {$this->event->title}.",
            'event_id' => $this->event->id,
        ];
    }
}
