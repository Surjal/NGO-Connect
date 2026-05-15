<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Jobs\ComputeUserRecommendations;
use App\Models\UserRecommendation;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    private RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the AI-powered recommendations for the user.
     * 
     * Gathers NGO and Event recommendations based on user activity 
     * and displays them in a dedicated view with explainable reasoning.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $stored = UserRecommendation::where('user_id', $user->id)->first();

        if (!$stored) {
            ComputeUserRecommendations::dispatchSync($user->id);
            $stored = UserRecommendation::where('user_id', $user->id)->first();
        }

        $recommendationData = $stored
            ? $this->recommendationService->loadStoredRecommendations($stored)
            : [
                'recommendedNgos' => collect(),
                'recommendedEvents' => collect(),
                'recommendedPosts' => collect(),
                'topCategory' => null,
            ];

        return view('people.recommendations.index', [
            'stored' => $stored,
            ...$recommendationData,
        ]);
    }
}
