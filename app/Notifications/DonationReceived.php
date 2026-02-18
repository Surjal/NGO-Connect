<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donation;

class DonationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Donation Has Been Processed')
            ->line("Thank you for your donation of NPR {$this->donation->donation_amount} to {$this->donation->ngo->name}.")
            ->line("Status: " . ucfirst($this->donation->status))
            ->action('View Your Donations', url('/donations'))
            ->line('We appreciate your support!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Your donation of NPR {$this->donation->donation_amount} to {$this->donation->ngo->name} has been processed (Status: " . ucfirst($this->donation->status) . ").",
            'donation_id' => $this->donation->id,
        ];
    }
}
