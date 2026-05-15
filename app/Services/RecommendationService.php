<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Event;
use App\Models\Follows;
use App\Models\Ngo;
use App\Models\Post;
use App\Models\PostHasComments;
use App\Models\PostHasLikes;
use App\Models\RecommendationLog;
use App\Models\User;
use App\Models\UserRecommendation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ==========================================================================
 *  AI-BASED RECOMMENDATION ENGINE
 * ==========================================================================
 *
 *  This service implements an intelligent recommendation engine that suggests
 *  relevant NGOs and Events to People users based on behavioral analysis.
 *
 *  HOW IT WORKS (explainable for academic viva):
 *  ─────────────────────────────────────────────
 *  1. INPUT DATA:
 *     - User activity signals: follows, likes, comments, donations, volunteering
 *
 *  2. INTEREST INFERENCE:
 *     - Each activity is weighted (donation=strongest, like=weakest)
 *     - Activities are aggregated by NGO category to build an interest profile
 *     - Result: a ranked list of categories the user cares about
 *
 *  3. WEIGHTED SCORING:
 *     - Every candidate NGO/Event receives a score based on multiple signals
 *     - Scores are additive: category match, donation similarity, engagement, etc.
 *     - Negative scores for already-engaged items (followed NGOs, expired events)
 *
 *  4. OUTPUT:
 *     - Top-scoring items with human-readable reason strings
 *     - Fallback to trending/popular items if user has no activity
 *
 *  This is a HEURISTIC-BASED AI system — it uses domain knowledge and
 *  weighted rules instead of machine learning models.
 * ==========================================================================
 */
class RecommendationService
{
    /*
    |--------------------------------------------------------------------------
    | Activity signal weights for interest profiling
    |--------------------------------------------------------------------------
    | Higher weight = stronger signal of user interest.
    | These are used when building the user's interest profile.
    */
    const WEIGHT_PROFILE   = 6;   // Strongest signal — explicit user preference
    const WEIGHT_DONATION   = 5;   // Very strong signal — user invested money
    const WEIGHT_VOLUNTEER  = 4;   // Very strong — user invested time
    const WEIGHT_FOLLOW     = 3;   // Strong — explicit interest declaration
    const WEIGHT_COMMENT    = 2;   // Medium — active engagement
    const WEIGHT_LIKE       = 1;   // Low-medium — passive engagement

    /*
    |--------------------------------------------------------------------------
    | Recommendation scoring weights
    |--------------------------------------------------------------------------
    | These control how much each factor contributes to the final score.
    */
    const SCORE_CATEGORY_MATCH       = 10;
    const SCORE_DONATED_SIMILAR      = 8;
    const SCORE_EVENT_NGO_MATCH      = 8;
    const SCORE_FOLLOWED_SIMILAR     = 7;
    const SCORE_VOLUNTEERED_SIMILAR  = 7;
    const SCORE_LIKED_SIMILAR        = 6;
    const SCORE_COMMENTED_SIMILAR    = 5;
    const SCORE_ENGAGED_NGO_EVENTS   = 5;
    const SCORE_UPCOMING_SOON        = 4;
    const SCORE_VOLUNTEERED_NGO      = 4;
    const SCORE_TRENDING             = 3;
    const PENALTY_ALREADY_ENGAGED    = -100;
    const PENALTY_EXPIRED            = -100;


    /**
     * ======================================================================
     *  BUILD USER INTEREST PROFILE
     * ======================================================================
     *
     * Gathers all activity signals and computes weighted scores per category.
     *
     * @param  User  $user  The logged-in People user
     * @return array        Interest profile with category scores and preferred NGOs
     */
    public function getUserInterestProfile(User $user): array
    {
        $categoryScores = [];
        $ngoScores = [];
        $interactionSummary = [
            'profile_interests' => 0,
            'follows' => 0,
            'likes' => 0,
            'comments' => 0,
            'donations' => 0,
            'volunteering' => 0,
        ];

        // ----- 0. Explicit Profile Interests (weight: 6) -----
        $preferredCategories = $user->preferred_categories ?? [];
        if (is_array($preferredCategories)) {
            foreach ($preferredCategories as $categoryName) {
                $this->addCategoryScore($categoryScores, $categoryName, self::WEIGHT_PROFILE);
                $interactionSummary['profile_interests']++;
            }
        }

        // ----- 1. Followed NGOs (weight: 3) -----
        $followedNgos = Ngo::whereIn('ngos.user_id', function ($q) use ($user) {
            $q->select('ngo_id')->from('follows')->where('user_id', $user->id);
        })->get(['ngos.id', 'ngos.user_id', 'ngos.category']);

        foreach ($followedNgos as $ngo) {
            $this->addCategoryScore($categoryScores, $ngo->category, self::WEIGHT_FOLLOW);
            $this->addNgoScore($ngoScores, $ngo->id, self::WEIGHT_FOLLOW);
            $interactionSummary['follows']++;
        }

        // ----- 2. Donations (weight: 5) -----
        // Note: donations.ngo_id references users.id (the NGO user account)
        $donatedNgoUserIds = Donation::where('user_id', $user->id)
            ->pluck('ngo_id')
            ->unique();

        $donatedNgos = Ngo::whereIn('ngos.user_id', $donatedNgoUserIds)->get(['ngos.id', 'ngos.user_id', 'ngos.category']);
        foreach ($donatedNgos as $ngo) {
            $this->addCategoryScore($categoryScores, $ngo->category, self::WEIGHT_DONATION);
            $this->addNgoScore($ngoScores, $ngo->id, self::WEIGHT_DONATION);
            $interactionSummary['donations']++;
        }

        // ----- 3. Volunteered Events (weight: 4) -----
        $volunteeredEvents = Event::whereIn('events.id', function ($q) use ($user) {
            $q->select('event_id')->from('event_has_volunteers')->where('user_id', $user->id);
        })->with('ngo')->get();

        foreach ($volunteeredEvents as $event) {
            $cat = $event->category ?? ($event->ngo->category ?? null);
            if ($cat) {
                $this->addCategoryScore($categoryScores, $cat, self::WEIGHT_VOLUNTEER);
            }
            if ($event->ngo) {
                $this->addNgoScore($ngoScores, $event->ngo->id, self::WEIGHT_VOLUNTEER);
            }
            $interactionSummary['volunteering']++;
        }

        // ----- 4. Liked Posts (weight: 1) -----
        $likedPostNgoUsers = DB::table('post_has_likes')
            ->join('posts', 'post_has_likes.post_id', '=', 'posts.id')
            ->where('post_has_likes.user_id', $user->id)
            ->pluck('posts.user_id')
            ->unique();

        $likedNgos = Ngo::whereIn('ngos.user_id', $likedPostNgoUsers)->get(['ngos.id', 'ngos.user_id', 'ngos.category']);
        foreach ($likedNgos as $ngo) {
            $this->addCategoryScore($categoryScores, $ngo->category, self::WEIGHT_LIKE);
            $this->addNgoScore($ngoScores, $ngo->id, self::WEIGHT_LIKE);
            $interactionSummary['likes']++;
        }

        // ----- 5. Commented Posts (weight: 2) -----
        $commentedPostNgoUsers = DB::table('post_has_comments')
            ->join('posts', 'post_has_comments.post_id', '=', 'posts.id')
            ->where('post_has_comments.user_id', $user->id)
            ->pluck('posts.user_id')
            ->unique();

        $commentedNgos = Ngo::whereIn('ngos.user_id', $commentedPostNgoUsers)->get(['ngos.id', 'ngos.user_id', 'ngos.category']);
        foreach ($commentedNgos as $ngo) {
            $this->addCategoryScore($categoryScores, $ngo->category, self::WEIGHT_COMMENT);
            $this->addNgoScore($ngoScores, $ngo->id, self::WEIGHT_COMMENT);
            $interactionSummary['comments']++;
        }

        // Sort categories and NGOs by score (highest first)
        arsort($categoryScores);
        arsort($ngoScores);

        return [
            'preferred_categories' => $categoryScores,
            'preferred_ngos' => $ngoScores,
            'interaction_summary' => $interactionSummary,
            'total_interactions' => array_sum($interactionSummary),
        ];
    }


    /**
     * ======================================================================
     *  RECOMMEND NGOs FOR USER
     * ======================================================================
     *
     * Scores all NGOs and returns the top recommendations with reasons.
     *
     * Scoring formula per NGO:
     * +10  category matches user interest
     * +8   user donated to NGOs with same category
     * +7   user follows NGOs with same category
     * +6   user liked posts from same-category NGOs
     * +5   user commented on same-category NGOs
     * +4   user volunteered in events from same-category NGOs
     * +3   NGO is trending (popular by follower/donation count)
     * -100 user already follows this NGO
     *
     * @param  User  $user
     * @param  int   $limit
     * @return Collection  NGOs with recommendation_score and recommendation_reason
     */
    public function recommendNgosForUser(User $user, int $limit = 6): Collection
    {
        $profile = $this->getUserInterestProfile($user);

        // If user has no activity, return trending NGOs as fallback
        if ($profile['total_interactions'] === 0) {
            return $this->getTrendingNgos($limit);
        }

        $preferredCategories = $profile['preferred_categories'];
        $preferredNgoIds = array_keys($profile['preferred_ngos']);

        // Get IDs of NGOs user already follows (to penalize)
        $followedNgoIds = Follows::where('user_id', $user->id)->pluck('ngo_id');
        $followedNgoModelIds = Ngo::whereIn('user_id', $followedNgoIds)->pluck('id')->toArray();
        $donatedCategories = $this->getDonatedCategories($user);
        $followedCategories = $this->getFollowedCategories($user);
        $likedCategories = $this->getEngagedPostCategories($user, 'post_has_likes');
        $commentedCategories = $this->getEngagedPostCategories($user, 'post_has_comments');
        $volunteerCategories = $this->getVolunteeredCategories($user);

        // Get all verified, non-suspended NGOs with eager-loaded counts
        $ngos = $this->applyVerifiedNgoConstraint(Ngo::query())
            ->withCount('followers')
            ->get();

        $scoredNgos = [];

        foreach ($ngos as $ngo) {
            $score = 0;
            $reasons = [];

            // --- Penalty: already followed ---
            if (in_array($ngo->id, $followedNgoModelIds)) {
                $score += self::PENALTY_ALREADY_ENGAGED;
                $reasons[] = 'Already followed';
            }

            // --- Category match ---
            if (!empty($ngo->category) && isset($preferredCategories[$ngo->category])) {
                $categoryStrength = $preferredCategories[$ngo->category];
                $score += self::SCORE_CATEGORY_MATCH * min($categoryStrength / 5, 3);
                $reasons[] = "Matches your interest in {$ngo->category}";
            }

            // --- Donated to similar category ---
            if (!empty($ngo->category) && in_array($ngo->category, $donatedCategories)) {
                $score += self::SCORE_DONATED_SIMILAR;
                $reasons[] = "You donated to similar {$ngo->category} NGOs";
            }

            // --- Followed similar category ---
            if (!empty($ngo->category) && in_array($ngo->category, $followedCategories)) {
                $score += self::SCORE_FOLLOWED_SIMILAR;
                $reasons[] = "You follow similar {$ngo->category} organizations";
            }

            // --- Liked posts from similar ---
            if (!empty($ngo->category) && in_array($ngo->category, $likedCategories)) {
                $score += self::SCORE_LIKED_SIMILAR;
            }

            // --- Commented on similar ---
            if (!empty($ngo->category) && in_array($ngo->category, $commentedCategories)) {
                $score += self::SCORE_COMMENTED_SIMILAR;
            }

            // --- Volunteered for events of similar NGOs ---
            if (!empty($ngo->category) && in_array($ngo->category, $volunteerCategories)) {
                $score += self::SCORE_VOLUNTEERED_NGO;
                $reasons[] = "You volunteered in related {$ngo->category} events";
            }

            // --- Trending bonus ---
            if ($ngo->followers_count >= 3) {
                $score += self::SCORE_TRENDING;
                $reasons[] = 'Popular in the community';
            }

            $ngo->recommendation_score = round($score, 1);
            $ngo->recommendation_reason = $this->buildPrimaryReason($reasons);

            $scoredNgos[] = $ngo;
        }

        // Sort by score descending, filter out heavily penalized
        return collect($scoredNgos)
            ->filter(fn ($ngo) => $ngo->recommendation_score > 0)
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }


    /**
     * ======================================================================
     *  RECOMMEND EVENTS FOR USER
     * ======================================================================
     *
     * Scores upcoming/active events and returns top recommendations.
     *
     * Scoring formula per Event:
     * +10  category matches user interest
     * +8   event's NGO category matches user interest
     * +7   user joined similar past events
     * +5   user engaged with posts from that NGO
     * +4   event is upcoming within 14 days
     * +3   event is popular (many volunteers)
     * -100 user already applied/joined
     * -100 event is expired/completed
     *
     * @param  User  $user
     * @param  int   $limit
     * @return Collection  Events with recommendation_score and recommendation_reason
     */
    public function recommendEventsForUser(User $user, int $limit = 6): Collection
    {
        $profile = $this->getUserInterestProfile($user);

        // Fallback for new users
        if ($profile['total_interactions'] === 0) {
            return $this->getPopularUpcomingEvents($limit);
        }

        $preferredCategories = $profile['preferred_categories'];

        // Get events user already volunteered for
        $volunteeredEventIds = DB::table('event_has_volunteers')
            ->where('user_id', $user->id)
            ->pluck('event_id')
            ->toArray();

        // Get all upcoming/live events with related data
        $events = Event::where('end_date', '>=', Carbon::now())
            ->with('ngo')
            ->withCount('volunteers')
            ->get();
        $volunteerCategories = $this->getVolunteeredCategories($user);
        $engagedNgoUserIds = $this->getEngagedNgoUserIds($user);

        $scoredEvents = [];

        foreach ($events as $event) {
            $score = 0;
            $reasons = [];

            // --- Penalty: already volunteered ---
            if (in_array($event->id, $volunteeredEventIds)) {
                $score += self::PENALTY_ALREADY_ENGAGED;
                $reasons[] = 'Already applied';
            }

            // --- Penalty: expired ---
            if (Carbon::parse($event->end_date)->isPast()) {
                $score += self::PENALTY_EXPIRED;
            }

            // --- Event category match ---
            $eventCategory = $event->category ?? ($event->ngo->category ?? null);
            if ($eventCategory && isset($preferredCategories[$eventCategory])) {
                $categoryStrength = $preferredCategories[$eventCategory];
                $score += self::SCORE_CATEGORY_MATCH * min($categoryStrength / 5, 3);
                $reasons[] = "Matches your interest in {$eventCategory}";
            }

            // --- Event's NGO matches preferred NGO types ---
            if ($event->ngo && !empty($event->ngo->category)) {
                $ngoCategory = $event->ngo->category;
                if (isset($preferredCategories[$ngoCategory])) {
                    $score += self::SCORE_EVENT_NGO_MATCH;
                    $reasons[] = "Organized by a {$ngoCategory} NGO you engage with";
                }
            }

            // --- User joined similar past events ---
            if ($eventCategory && in_array($eventCategory, $volunteerCategories)) {
                $score += self::SCORE_VOLUNTEERED_SIMILAR;
                $reasons[] = "Similar to events you've volunteered in";
            }

            // --- User engaged with posts from this event's NGO ---
            if ($event->user_id) {
                if (in_array($event->user_id, $engagedNgoUserIds)) {
                    $score += self::SCORE_ENGAGED_NGO_EVENTS;
                    $reasons[] = "You engage with this NGO's posts";
                }
            }

            // --- Upcoming soon bonus (within 14 days) ---
            $daysUntilStart = Carbon::now()->diffInDays(Carbon::parse($event->start_date), false);
            if ($daysUntilStart >= 0 && $daysUntilStart <= 14) {
                $score += self::SCORE_UPCOMING_SOON;
                $reasons[] = 'Starting soon';
            }

            // --- Popularity bonus ---
            if ($event->volunteers_count >= 3) {
                $score += self::SCORE_TRENDING;
                $reasons[] = 'Popular event';
            }

            $event->recommendation_score = round($score, 1);
            $event->recommendation_reason = $this->buildPrimaryReason($reasons);

            $scoredEvents[] = $event;
        }

        return collect($scoredEvents)
            ->filter(fn ($event) => $event->recommendation_score > 0)
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }


    /**
     * ======================================================================
     *  POST RECOMMENDATIONS
     * ======================================================================
     *
     * Similar to NGOs and Events, scores Posts based on category matching
     * and NGO engagement.
     */
    public function recommendPostsForUser(User $user, int $limit = 6): Collection
    {
        $profile = $this->getUserInterestProfile($user);

        // If user has no activity, return trending posts
        if ($profile['total_interactions'] === 0) {
            return $this->getTrendingPosts($limit);
        }

        $preferredCategories = $profile['preferred_categories'];
        $preferredNgoIds = array_keys($profile['preferred_ngos']);

        // Get recent posts (last 30 days) from verified NGOs
        $posts = Post::where('created_at', '>=', Carbon::now()->subDays(30))
            ->whereHas('user', function ($q) {
                $q->where('role_id', 1); // Must be NGO
            })
            ->whereHas('user.ngo', function ($q) {
                $this->applyVerifiedNgoConstraint($q);
            })
            ->with(['user.ngo', 'medias', 'likes', 'comments']) // Eager load relationships
            ->get();

        $scoredPosts = [];

        foreach ($posts as $post) {
            $score = 0;
            $reasons = [];
            $ngo = $post->user->ngo;

            if (!$ngo) continue;

            // --- 1. Category Match ---
            if (isset($preferredCategories[$ngo->category])) {
                // Add points proportional to how much they like this category
                $categoryWeight = $preferredCategories[$ngo->category]; // e.g. 5 points
                $score += (self::SCORE_CATEGORY_MATCH * ($categoryWeight / 10));
                
                $reasons[] = "Matches your interest in {$ngo->category}";
            }

            // --- 2. NGO Engagement Match ---
            if (in_array($ngo->id, $preferredNgoIds)) {
                $score += self::SCORE_ENGAGED_NGO_EVENTS;
                $reasons[] = 'From an NGO you interact with';
            }

            // --- Popularity bonus ---
            if ($post->likes->count() >= 5 || $post->comments->count() >= 3) {
                $score += self::SCORE_TRENDING;
                $reasons[] = 'Popular post';
            }

            // Only recommend posts with a score
            if ($score > 0) {
                $post->recommendation_score = round($score, 1);
                $post->recommendation_reason = $this->buildPrimaryReason($reasons);
                $scoredPosts[] = $post;
            }
        }

        return collect($scoredPosts)
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }


    /**
     * ======================================================================
     *  FALLBACK: TRENDING POSTS
     * ======================================================================
     */
    public function getTrendingPosts(int $limit = 6): Collection
    {
        // Get recent posts sorted by raw engagement (likes + comments)
        $posts = Post::where('created_at', '>=', Carbon::now()->subDays(30))
            ->whereHas('user', function ($q) {
                $q->where('role_id', 1);
            })
            ->whereHas('user.ngo', function ($q) {
                $this->applyVerifiedNgoConstraint($q);
            })
            ->withCount(['likes', 'comments'])
            ->with(['user.ngo', 'medias'])
            ->get()
            ->map(function ($post) {
                $post->recommendation_score = $post->likes_count + ($post->comments_count * 2);
                $post->recommendation_reason = 'Trending in the community';
                return $post;
            })
            ->filter(fn($post) => $post->recommendation_score > 0) // Only return posts with at least some engagement if trending
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
            
        // If there are literally no engaged posts, just return latest
        if ($posts->isEmpty()) {
            $posts = Post::whereHas('user', function ($q) {
                $q->where('role_id', 1);
            })
            ->whereHas('user.ngo', function ($q) {
                $this->applyVerifiedNgoConstraint($q);
            })
            ->with(['user.ngo', 'medias'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($post) {
                $post->recommendation_score = 1;
                $post->recommendation_reason = 'Recent update';
                return $post;
            });
        }

        return $posts;
    }


    /**
     * ======================================================================
     *  FALLBACK: TRENDING NGOs
     * ======================================================================
     *
     * Returns popular NGOs for users with no activity history.
     * Popularity = follower count + donation count.
     */
    public function getTrendingNgos(int $limit = 6): Collection
    {
        $ngos = $this->applyVerifiedNgoConstraint(Ngo::query())
            ->withCount(['followers', 'donations'])
            ->get()
            ->map(function ($ngo) {
                $ngo->recommendation_score = $ngo->followers_count + $ngo->donations_count;
                $ngo->recommendation_reason = 'Trending in the community';
                return $ngo;
            })
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();

        return $ngos;
    }


    /**
     * ======================================================================
     *  FALLBACK: POPULAR UPCOMING EVENTS
     * ======================================================================
     *
     * Returns popular upcoming events for users with no activity history.
     */
    public function getPopularUpcomingEvents(int $limit = 6): Collection
    {
        $events = Event::where('end_date', '>=', Carbon::now())
            ->with('ngo')
            ->withCount('volunteers')
            ->get()
            ->map(function ($event) {
                $daysUntilStart = Carbon::now()->diffInDays(Carbon::parse($event->start_date), false);
                $urgencyBonus = ($daysUntilStart >= 0 && $daysUntilStart <= 14) ? 4 : 0;
                $event->recommendation_score = $event->volunteers_count + $urgencyBonus;
                $event->recommendation_reason = 'Popular upcoming event';
                return $event;
            })
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();

        return $events;
    }


    /**
     * ======================================================================
     *  LOG RECOMMENDATIONS (optional, for debugging/demo)
     * ======================================================================
     */
    public function logRecommendations(User $user, Collection $items, string $type): void
    {
        $logs = $items->map(fn ($item) => [
            'user_id' => $user->id,
            'recommendable_type' => $type,
            'recommendable_id' => $item->id,
            'score' => $item->recommendation_score,
            'reason' => $item->recommendation_reason,
            'created_at' => now(),
        ])->toArray();

        if (!empty($logs)) {
            RecommendationLog::insert($logs);
        }
    }

    /**
     * Compute, log, and persist recommendation references for fast reads.
     */
    public function computeAndStoreRecommendations(User $user, int $limit = 6): UserRecommendation
    {
        $user = $user->fresh();

        $recommendedNgos = $this->recommendNgosForUser($user, $limit);
        $recommendedEvents = $this->recommendEventsForUser($user, $limit);
        $recommendedPosts = $this->recommendPostsForUser($user, $limit);

        RecommendationLog::where('user_id', $user->id)->delete();
        $this->logRecommendations($user, $recommendedNgos, Ngo::class);
        $this->logRecommendations($user, $recommendedEvents, Event::class);
        $this->logRecommendations($user, $recommendedPosts, Post::class);

        $interestProfile = $this->getUserInterestProfile($user);
        $topCategory = !empty($interestProfile['preferred_categories'])
            ? array_key_first($interestProfile['preferred_categories'])
            : null;

        return UserRecommendation::updateOrCreate(
            ['user_id' => $user->id],
            [
                'recommendations' => [
                    'ngos' => $this->serializeRecommendations($recommendedNgos),
                    'events' => $this->serializeRecommendations($recommendedEvents),
                    'posts' => $this->serializeRecommendations($recommendedPosts),
                    'top_category' => $topCategory,
                ],
                'computed_at' => now(),
            ]
        );
    }

    /**
     * Load stored recommendation references and hydrate them in bulk.
     */
    public function loadStoredRecommendations(UserRecommendation $stored): array
    {
        $payload = $stored->recommendations ?? [];

        return [
            'recommendedNgos' => $this->hydrateNgoRecommendations($payload['ngos'] ?? []),
            'recommendedEvents' => $this->hydrateEventRecommendations($payload['events'] ?? []),
            'recommendedPosts' => $this->hydratePostRecommendations($payload['posts'] ?? []),
            'topCategory' => $payload['top_category'] ?? null,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PRIVATE HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /** Add score to a category in the scores array */
    private function addCategoryScore(array &$scores, ?string $category, int $weight): void
    {
        if ($category) {
            $scores[$category] = ($scores[$category] ?? 0) + $weight;
        }
    }

    /** Add score to an NGO in the scores array */
    private function addNgoScore(array &$scores, int $ngoId, int $weight): void
    {
        $scores[$ngoId] = ($scores[$ngoId] ?? 0) + $weight;
    }

    /** Store only the information needed to rebuild recommendation cards fast. */
    private function serializeRecommendations(Collection $items): array
    {
        return $items->map(fn ($item) => [
            'id' => $item->id,
            'score' => $item->recommendation_score,
            'reason' => $item->recommendation_reason,
        ])->values()->all();
    }

    /** Get categories of NGOs the user has donated to */
    private function getDonatedCategories(User $user): array
    {
        return Ngo::whereIn('ngos.user_id',
            Donation::where('user_id', $user->id)
                ->pluck('ngo_id')
        )->pluck('category')->filter()->unique()->toArray();
    }

    /** Get categories of NGOs the user follows */
    private function getFollowedCategories(User $user): array
    {
        return Ngo::whereIn('ngos.user_id',
            Follows::where('user_id', $user->id)->pluck('ngo_id')
        )->pluck('category')->filter()->unique()->toArray();
    }

    /** Get categories of NGOs whose posts the user engaged with */
    private function getEngagedPostCategories(User $user, string $table): array
    {
        $ngoUserIds = DB::table($table)
            ->join('posts', $table.'.post_id', '=', 'posts.id')
            ->where($table.'.user_id', $user->id)
            ->pluck('posts.user_id')
            ->unique();

        return Ngo::whereIn('ngos.user_id', $ngoUserIds)->pluck('category')->filter()->unique()->toArray();
    }

    /** Apply verified and non-suspended constraints to an NGO query */
    private function applyVerifiedNgoConstraint($query)
    {
        return $query->where('verified', true)
            ->where(function ($q) {
                $q->where('suspended', false)->orWhereNull('suspended');
            });
    }

    /** Get categories of events the user volunteered in */
    private function getVolunteeredCategories(User $user): array
    {
        return Event::whereIn('events.id',
            DB::table('event_has_volunteers')
                ->where('user_id', $user->id)
                ->pluck('event_id')
        )->join('ngos', function ($join) {
            $join->on('events.user_id', '=', 'ngos.user_id');
        })->pluck('ngos.category')->filter()->unique()->toArray();
    }

    /** Get NGO user IDs that the user has engaged with (liked or commented) */
    private function getEngagedNgoUserIds(User $user): array
    {
        $liked = DB::table('post_has_likes')
            ->join('posts', 'post_has_likes.post_id', '=', 'posts.id')
            ->where('post_has_likes.user_id', $user->id)
            ->pluck('posts.user_id');

        $commented = DB::table('post_has_comments')
            ->join('posts', 'post_has_comments.post_id', '=', 'posts.id')
            ->where('post_has_comments.user_id', $user->id)
            ->pluck('posts.user_id');

        return $liked->merge($commented)->unique()->toArray();
    }

    /** Hydrate stored NGO recommendations while preserving stored order and scores. */
    private function hydrateNgoRecommendations(array $items): Collection
    {
        $ids = collect($items)->pluck('id')->all();
        if (empty($ids)) {
            return collect();
        }

        $models = $this->applyVerifiedNgoConstraint(Ngo::query())
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return $this->attachStoredMetadata($items, $models);
    }

    /** Hydrate stored event recommendations while preserving stored order and scores. */
    private function hydrateEventRecommendations(array $items): Collection
    {
        $ids = collect($items)->pluck('id')->all();
        if (empty($ids)) {
            return collect();
        }

        $models = Event::with('ngo')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return $this->attachStoredMetadata($items, $models);
    }

    /** Hydrate stored post recommendations while preserving stored order and scores. */
    private function hydratePostRecommendations(array $items): Collection
    {
        $ids = collect($items)->pluck('id')->all();
        if (empty($ids)) {
            return collect();
        }

        $models = Post::with(['user.ngo', 'medias'])
            ->withCount(['likes', 'comments'])
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return $this->attachStoredMetadata($items, $models);
    }

    /** Attach stored score metadata to hydrated models. */
    private function attachStoredMetadata(array $items, Collection $models): Collection
    {
        return collect($items)
            ->map(function (array $item) use ($models) {
                $model = $models->get($item['id']);

                if (!$model) {
                    return null;
                }

                $model->recommendation_score = (float) $item['score'];
                $model->recommendation_reason = $item['reason'] ?? 'Recommended for you';

                return $model;
            })
            ->filter()
            ->values();
    }

    /**
     * Build the best human-readable reason from the reasons list.
     * Picks the most specific reason while keeping it concise.
     */
    private function buildPrimaryReason(array $reasons): string
    {
        // Filter out penalty-related reasons
        $positive = array_filter($reasons, fn ($r) =>
            !str_contains($r, 'Already')
        );

        if (empty($positive)) {
            return 'Recommended for you';
        }

        // Return the first (most relevant) reason
        return reset($positive);
    }
}
