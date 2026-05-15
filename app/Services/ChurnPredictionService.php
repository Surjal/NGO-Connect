<?php

namespace App\Services;

use App\Models\ChurnPrediction;
use App\Models\Event;
use App\Models\User;
use App\Notifications\VolunteerChurnAlert;
use Illuminate\Support\Collection;

class ChurnPredictionService
{
    private const INTERCEPT = -1.2;

    private const COEFFICIENTS = [
        'days_since_last_attendance' => 0.018,
        'total_events_attended' => -0.15,
        'attendance_rate' => -1.10,
        'total_events_registered' => -0.08,
        'posts_liked_last_30_days' => -0.20,
        'comments_made_last_30_days' => -0.25,
        'circle_replies_last_30_days' => -0.22,
        'messages_sent_last_30_days' => -0.18,
        'badges_earned_total' => -0.12,
    ];

    private int $lastBatchCount = 0;

    public function __construct(
        private readonly ChurnFeatureExtractor $featureExtractor
    ) {
    }

    public function predictRiskScore(array $features): float
    {
        $linearScore = self::INTERCEPT;

        foreach (self::COEFFICIENTS as $feature => $coefficient) {
            $linearScore += ((float) ($features[$feature] ?? 0.0)) * $coefficient;
        }

        if ($linearScore >= 0) {
            $exp = exp(-$linearScore);
            return (float) (1 / (1 + $exp));
        }

        $exp = exp($linearScore);

        return (float) ($exp / (1 + $exp));
    }

    public function getRiskLevel(float $score): string
    {
        if ($score < 0.35) {
            return 'low';
        }

        if ($score <= 0.65) {
            return 'medium';
        }

        return 'high';
    }

    public function predictForVolunteer(int $volunteerId, int $ngoId): array
    {
        $features = $this->featureExtractor->extractFeatures($volunteerId, $ngoId);
        $score = $this->predictRiskScore($features);
        $riskLevel = $this->getRiskLevel($score);

        $prediction = ChurnPrediction::updateOrCreate(
            [
                'volunteer_id' => $volunteerId,
                'ngo_id' => $ngoId,
            ],
            [
                'risk_score' => $score,
                'risk_level' => $riskLevel,
                'feature_snapshot' => $features,
                'predicted_at' => now(),
            ]
        );

        $volunteer = User::findOrFail($volunteerId);
        $ngo = User::findOrFail($ngoId);

        if ($riskLevel === 'high') {
            $ngo->notify(new VolunteerChurnAlert($volunteer, $score, $features));
        }

        return [
            'volunteer_id' => $volunteerId,
            'ngo_id' => $ngoId,
            'risk_score' => (float) $prediction->risk_score,
            'risk_level' => $riskLevel,
            'feature_snapshot' => array_map('floatval', $features),
            'predicted_at' => $prediction->predicted_at,
        ];
    }

    public function runBatchPredictions(): void
    {
        $this->lastBatchCount = 0;

        $activePairs = $this->getActiveVolunteerNgoPairs();

        foreach ($activePairs as $pair) {
            $this->predictForVolunteer((int) $pair['volunteer_id'], (int) $pair['ngo_id']);
            $this->lastBatchCount++;
        }
    }

    public function runPredictionsForNgo(int $ngoId): int
    {
        $count = 0;

        $activePairs = $this->getActiveVolunteerNgoPairs()
            ->where('ngo_id', $ngoId)
            ->values();

        foreach ($activePairs as $pair) {
            $this->predictForVolunteer((int) $pair['volunteer_id'], (int) $pair['ngo_id']);
            $count++;
        }

        return $count;
    }

    public function refreshVolunteerNgoPairIfEligible(int $volunteerId, int $ngoId): ?array
    {
        $registrationCount = Event::where('user_id', $ngoId)
            ->whereHas('volunteers', function ($query) use ($volunteerId) {
                $query->where('users.id', $volunteerId);
            })
            ->count();

        if ($registrationCount < 2) {
            return null;
        }

        return $this->predictForVolunteer($volunteerId, $ngoId);
    }

    public function getLastBatchCount(): int
    {
        return $this->lastBatchCount;
    }

    public function ensureDashboardPredictionsForNgo(int $ngoId): array
    {
        $latestPrediction = ChurnPrediction::where('ngo_id', $ngoId)
            ->latest('predicted_at')
            ->first();

        if (!$latestPrediction) {
            $this->runPredictionsForNgo($ngoId);
        }

        return $this->getDashboardDataForNgo($ngoId);
    }

    public function getDashboardDataForNgo(int $ngoId): array
    {
        $predictions = ChurnPrediction::with('volunteer')
            ->where('ngo_id', $ngoId)
            ->orderByDesc('risk_score')
            ->get();

        return [
            'high_count' => $predictions->where('risk_level', 'high')->count(),
            'medium_count' => $predictions->where('risk_level', 'medium')->count(),
            'low_count' => $predictions->where('risk_level', 'low')->count(),
            'top_predictions' => $predictions->take(5),
        ];
    }

    private function getActiveVolunteerNgoPairs(): Collection
    {
        $pairs = collect();

        $events = Event::with('volunteers:id')
            ->get(['id', 'user_id']);

        foreach ($events as $event) {
            foreach ($event->volunteers as $volunteer) {
                $key = $volunteer->id . ':' . $event->user_id;

                if (!$pairs->has($key)) {
                    $pairs->put($key, [
                        'volunteer_id' => (int) $volunteer->id,
                        'ngo_id' => (int) $event->user_id,
                        'registrations' => 0,
                    ]);
                }

                $pair = $pairs->get($key);
                $pair['registrations']++;
                $pairs->put($key, $pair);
            }
        }

        return $pairs
            ->filter(fn (array $pair) => $pair['registrations'] >= 2)
            ->values();
    }
}
