<?php

namespace App\Services;

use App\Models\Follows;
use App\Models\Post;
use App\Models\PostHasLikes;
use App\Models\PostHasReports;
use App\Models\User;
use App\Models\Ngo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FeedService
{
    private const SCORE_FOLLOWING = 50;
    private const SCORE_LOCATION = 30;
    private const SCORE_CATEGORY = 20;
    private const SCORE_INFERRED_CATEGORY = 15;
    private const SCORE_FAVORITED = 10;
    private const SCORE_RECENCY_DAYS = 14;
    private const PENALTY_PER_REPORT = 5;
    private const DEFAULT_MAX_PER_NGO = 3;

    private function merge(array $left, array $right, callable $compare): array
    {
        $result = [];
        $i = $j = 0;

        while ($i < count($left) && $j < count($right)) {
            if ($compare($left[$i], $right[$j]) <= 0) {
                $result[] = $left[$i++];
            } else {
                $result[] = $right[$j++];
            }
        }

        while ($i < count($left)) {
            $result[] = $left[$i++];
        }
        while ($j < count($right)) {
            $result[] = $right[$j++];
        }

        return $result;
    }

    private function mergeSort(array $items, callable $compare): array
    {
        if (count($items) <= 1) {
            return $items;
        }

        $middle = (int)(count($items) / 2);
        $left = array_slice($items, 0, $middle);
        $right = array_slice($items, $middle);

        $left = $this->mergeSort($left, $compare);
        $right = $this->mergeSort($right, $compare);

        return $this->merge($left, $right, $compare);
    }

    private function inferCategoryPreferences(
        int $userId,
        Collection $followedNgoIds,
        Collection $likedPostIds,
        Collection $favoritedNgoIds
    ): array {
        $interactedNgoUserIds = collect();

        $interactedNgoUserIds = $interactedNgoUserIds->merge($followedNgoIds->keys());

        $favNgoUserIds = Ngo::whereIn('id', $favoritedNgoIds->keys())
            ->pluck('user_id');
        $interactedNgoUserIds = $interactedNgoUserIds->merge($favNgoUserIds);

        $likedNgoUserIds = Post::whereIn('id', $likedPostIds->keys())
            ->pluck('user_id')
            ->unique();
        $interactedNgoUserIds = $interactedNgoUserIds->merge($likedNgoUserIds);

        $categories = Ngo::whereIn('user_id', $interactedNgoUserIds->unique())
            ->whereNotNull('category')
            ->pluck('category')
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(5)
            ->all();

        return $categories;
    }

    public function sortPostsForFeed(Collection $posts, ?int $userId, int $maxPerNgo = self::DEFAULT_MAX_PER_NGO): Collection
    {
        $user = null;
        $likedPostIds = collect();
        $followedNgoIds = collect();
        $userReportedPostIds = collect();
        $favoritedNgoIds = collect();
        $userLocation = null;
        $userCategories = [];
        $inferredCategories = [];

        if ($userId) {
            $user = User::with('ngo')->find($userId);

            $likedPostIds = PostHasLikes::where('user_id', $userId)
                ->pluck('post_id')
                ->flip();

            $followedNgoIds = Follows::where('user_id', $userId)
                ->pluck('ngo_id')
                ->flip();

            $userReportedPostIds = PostHasReports::where('user_id', $userId)
                ->pluck('post_id')
                ->flip();

            $favoritedNgoIds = DB::table('user_ngo_favorites')
                ->where('user_id', $userId)
                ->pluck('ngo_id')
                ->flip();

            $userLocation = $user->location ?? null;
            $userCategories = $user->preferred_categories ?? [];

            $inferredCategories = $this->inferCategoryPreferences(
                $userId, $followedNgoIds, $likedPostIds, $favoritedNgoIds
            );
        }

        $reportCounts = PostHasReports::select('post_id', DB::raw('COUNT(*) as cnt'))
            ->whereIn('post_id', $posts->pluck('id')->all())
            ->groupBy('post_id')
            ->pluck('cnt', 'post_id');

        $ngoUserIds = $posts->pluck('user_id')->unique();
        $ngoData = Ngo::whereIn('user_id', $ngoUserIds)
            ->get(['user_id', 'category', 'registration_district'])
            ->keyBy('user_id');

        $now = now();

        $filtered = $posts->filter(function ($post) use ($userReportedPostIds) {
            return !$userReportedPostIds->has($post->id);
        });

        $filtered->each(function ($post) use (
            $likedPostIds, $followedNgoIds, $reportCounts,
            $favoritedNgoIds, $ngoData, $userLocation,
            $userCategories, $inferredCategories, $now
        ) {
            $isFollowing = $followedNgoIds->has($post->user_id);
            $isLiked = $likedPostIds->has($post->id);
            $reports = $reportCounts->get($post->id, 0);
            $isFavorited = $favoritedNgoIds->has($post->user_id);

            $post->setAttribute('is_liked', $isLiked);
            $post->setAttribute('is_following', $isFollowing);
            $post->setAttribute('reports_count', $reports);
            $post->setAttribute('user_reported', false);

            $score = 0;

            if ($isFollowing) {
                $score += self::SCORE_FOLLOWING;
            }

            $ngo = $ngoData->get($post->user_id);
            if ($userLocation && $ngo && stripos($ngo->registration_district, $userLocation) !== false) {
                $score += self::SCORE_LOCATION;
            }

            if (!empty($userCategories) && $ngo && in_array($ngo->category, $userCategories)) {
                $score += self::SCORE_CATEGORY;
            }

            if (!empty($inferredCategories) && $ngo && in_array($ngo->category, $inferredCategories)) {
                $score += self::SCORE_INFERRED_CATEGORY;
            }

            if ($isFavorited) {
                $score += self::SCORE_FAVORITED;
            }

            $ageInDays = $post->created_at->diffInDays($now);
            $score += max(0, 10 - (int)($ageInDays * 10 / self::SCORE_RECENCY_DAYS));

            $score -= $reports * self::PENALTY_PER_REPORT;

            $post->setAttribute('recommendation_score', $score);
        });

        $sortedModels = $this->mergeSort($filtered->all(), function ($a, $b) {
            $scoreDiff = $b->recommendation_score - $a->recommendation_score;
            if ($scoreDiff !== 0) {
                return $scoreDiff;
            }
            return strcmp($b->created_at, $a->created_at);
        });

        $final = [];
        $ngoCount = [];

        foreach ($sortedModels as $post) {
            $ngoId = $post->user_id;
            $ngoCount[$ngoId] = ($ngoCount[$ngoId] ?? 0) + 1;

            if ($ngoCount[$ngoId] <= $maxPerNgo) {
                $final[] = $post;
            }
        }

        return collect($final);
    }
}
