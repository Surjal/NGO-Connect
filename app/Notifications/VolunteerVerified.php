<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VolunteerVerified extends Notification
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Volunteer Verified - Thank You for Your Participation!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your volunteer status for "' . $this->event->title . '" has been officially verified and accepted.')
            ->line('Thank you for your participation in this event! Your commitment helps us make a significant positive impact in our community.')
            ->line('**Event Details:**')
            ->line('• Date: ' . $this->event->start_date->format('F j, Y, g:i A'))
            ->line('• Location: ' . $this->event->location)
            ->action('View Event & Certificate', route('people.volunteer.details', $this->event->id))
            ->line('We look forward to seeing the results of your hard work. Thank you for being an essential part of NGO Connect!');
    }

    public function toArray($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'message' => 'Your volunteer registration for "' . $this->event->title . '" has been confirmed.',
        ];
    }
}
