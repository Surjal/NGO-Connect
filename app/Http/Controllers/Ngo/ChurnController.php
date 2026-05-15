<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\ChurnPrediction;
use App\Models\Event;
use App\Models\User;
use App\Services\ChurnPredictionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChurnController extends Controller
{
    public function __construct(
        private readonly ChurnPredictionService $churnPredictionService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $ngoId = (int) $request->user()->id;

        $predictions = ChurnPrediction::with('volunteer')
            ->where('ngo_id', $ngoId)
            ->orderByDesc('risk_score')
            ->get()
            ->map(function (ChurnPrediction $prediction) {
                $snapshot = $prediction->feature_snapshot ?? [];

                return [
                    'volunteer_id' => $prediction->volunteer_id,
                    'volunteer_name' => $prediction->volunteer->name ?? 'Unknown',
                    'volunteer_email' => $prediction->volunteer->email ?? null,
                    'risk_score' => (float) $prediction->risk_score,
                    'risk_level' => $prediction->risk_level,
                    'days_since_last_attendance' => (float) ($snapshot['days_since_last_attendance'] ?? 0.0),
                    'total_events_attended' => (float) ($snapshot['total_events_attended'] ?? 0.0),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $predictions,
            'message' => 'Churn predictions loaded successfully.',
        ]);
    }

    public function show(Request $request, int $volunteerId): JsonResponse
    {
        $prediction = ChurnPrediction::with(['volunteer', 'ngo'])
            ->where('ngo_id', $request->user()->id)
            ->where('volunteer_id', $volunteerId)
            ->first();

        if (!$prediction) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Prediction not found for this volunteer.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'volunteer_id' => $prediction->volunteer_id,
                'volunteer_name' => $prediction->volunteer->name ?? 'Unknown',
                'volunteer_email' => $prediction->volunteer->email ?? null,
                'ngo_id' => $prediction->ngo_id,
                'risk_score' => (float) $prediction->risk_score,
                'risk_level' => $prediction->risk_level,
                'feature_snapshot' => array_map('floatval', $prediction->feature_snapshot ?? []),
                'predicted_at' => optional($prediction->predicted_at)->toISOString(),
            ],
            'message' => 'Prediction detail loaded successfully.',
        ]);
    }

    public function refresh(Request $request, int $volunteerId): JsonResponse
    {
        $ngoId = (int) $request->user()->id;

        $hasRegistration = Event::where('user_id', $ngoId)
            ->whereHas('volunteers', function ($query) use ($volunteerId) {
                $query->where('users.id', $volunteerId);
            })
            ->exists();

        if (!$hasRegistration) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Volunteer is not registered with this NGO.',
            ], 404);
        }

        $prediction = $this->churnPredictionService->predictForVolunteer($volunteerId, $ngoId);

        return response()->json([
            'success' => true,
            'data' => [
                'volunteer_id' => $prediction['volunteer_id'],
                'ngo_id' => $prediction['ngo_id'],
                'risk_score' => (float) $prediction['risk_score'],
                'risk_level' => $prediction['risk_level'],
                'feature_snapshot' => array_map('floatval', $prediction['feature_snapshot']),
                'predicted_at' => optional($prediction['predicted_at'])->toISOString(),
            ],
            'message' => 'Prediction refreshed successfully.',
        ]);
    }
}
