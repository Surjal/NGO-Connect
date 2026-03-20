<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
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

        // Get recommended NGOs, Events, and Posts
        $recommendedNgos = $this->recommendationService->recommendNgosForUser($user, 6);
        $recommendedEvents = $this->recommendationService->recommendEventsForUser($user, 6);
        $recommendedPosts = $this->recommendationService->recommendPostsForUser($user, 6);

        // Clear previous recommendations for this user to keep the DB clean
        \App\Models\RecommendationLog::where('user_id', $user->id)->delete();

        // Store current recommendations in the database
        $this->recommendationService->logRecommendations($user, $recommendedNgos, \App\Models\Ngo::class);
        $this->recommendationService->logRecommendations($user, $recommendedEvents, \App\Models\Event::class);
        $this->recommendationService->logRecommendations($user, $recommendedPosts, \App\Models\Post::class);

        // Optional: Get the user's interest profile to display what the AI learned
        $interestProfile = $this->recommendationService->getUserInterestProfile($user);
        $topCategory = null;
        if (!empty($interestProfile['preferred_categories'])) {
            $topCategory = array_key_first($interestProfile['preferred_categories']);
        }

        return view('people.recommendations.index', compact(
            'recommendedNgos', 
            'recommendedEvents',
            'recommendedPosts',
            'interestProfile',
            'topCategory'
        ));
    }
}
