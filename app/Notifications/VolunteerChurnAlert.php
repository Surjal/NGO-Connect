<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VolunteerChurnAlert extends Notification
{
    use Queueable;

    public function __construct(
        private readonly User $volunteer,
        private readonly float $riskScore,
        private readonly array $features
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $daysSinceLastAttendance = (int) ($this->features['days_since_last_attendance'] ?? 0);
        $riskPercentage = number_format($this->riskScore * 100, 1);

        return (new MailMessage)
            ->subject('A volunteer may be disengaging from your NGO')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->volunteer->name . ' has been flagged as a high retention risk.')
            ->line('Risk score: ' . $riskPercentage . '%.')
            ->line('Days since last attendance: ' . $daysSinceLastAttendance . '.')
            ->line('Consider inviting them to your next upcoming event.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'churn_alert',
            'volunteer_id' => $this->volunteer->id,
            'volunteer_name' => $this->volunteer->name,
            'risk_score' => $this->riskScore,
            'risk_level' => 'high',
        ];
    }
}
