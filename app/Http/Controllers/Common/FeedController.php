<?php

namespace App\Http\Controllers\Common;

use App\Jobs\ComputeUserRecommendations;
use App\Models\Post;
use App\Models\Media;
use App\Models\Follows;
use App\Models\PostHasLikes;
use App\Models\PostHasComments;
use App\Models\PostHasReports;   // <-- your real reports table
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    /* --------------------------------------------------------------
     *  Merge-Sort implementation (stable, O(n log n))
     * ------------------------------------------------------------ */
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

        while ($i < count($left))  $result[] = $left[$i++];
        while ($j < count($right)) $result[] = $right[$j++];

        return $result;
    }

    private function mergeSort(array $items, callable $compare): array
    {
        if (count($items) <= 1) {
            return $items;
        }

        $middle = (int)(count($items) / 2);
        $left   = array_slice($items, 0, $middle);
        $right  = array_slice($items, $middle);

        $left  = $this->mergeSort($left, $compare);
        $right = $this->mergeSort($right, $compare);

        return $this->merge($left, $right, $compare);
    }

    /* --------------------------------------------------------------
     *  Core algorithm – weighted scoring recommendation
     *
     *  Scoring factors:
     *    +50  user follows this NGO
     *    +30  NGO is in the same district as user
     *    +20  NGO category matches explicit user preference
     *    +15  NGO category matches inferred preference (likes/follows/favs)
     *    +10  user favorited this NGO
     *    +0‑10  recency bonus (linear decay over 14 days)
     *    −5   per report
     *
     *  Max 3 posts per NGO to ensure diversity.
     * ------------------------------------------------------------ */
    public function sortPostsForFeed(Collection $posts, ?int $userId, int $maxPerNgo = 3): Collection
    {
        // ── Gather user signals ──────────────────────────────────
        if ($userId) {
            $user = \App\Models\User::with('ngo')->find($userId);

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

            // User's explicit preferences
            $userLocation   = $user->location ?? null;
            $userCategories = $user->preferred_categories ?? [];

            // ── Infer preferences from behaviour ─────────────────
            $inferredCategories = $this->inferCategoryPreferences(
                $userId, $followedNgoIds, $likedPostIds, $favoritedNgoIds
            );
        } else {
            $likedPostIds        = collect([]);
            $followedNgoIds      = collect([]);
            $userReportedPostIds = collect([]);
            $favoritedNgoIds     = collect([]);
            $userLocation        = null;
            $userCategories      = [];
            $inferredCategories  = [];
        }

        // ── Report counts (batch) ────────────────────────────────
        $reportCounts = PostHasReports::select('post_id', DB::raw('COUNT(*) as cnt'))
            ->whereIn('post_id', $posts->pluck('id')->all())
            ->groupBy('post_id')
            ->pluck('cnt', 'post_id');

        // ── Pre‑load NGO metadata for scoring ────────────────────
        $ngoUserIds = $posts->pluck('user_id')->unique();
        $ngoData    = \App\Models\Ngo::whereIn('user_id', $ngoUserIds)
            ->get(['user_id', 'category', 'registration_district'])
            ->keyBy('user_id');

        // ── Filter & score ───────────────────────────────────────
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
            $isLiked     = $likedPostIds->has($post->id);
            $reports     = $reportCounts->get($post->id, 0);
            $isFavorited = $favoritedNgoIds->has($post->user_id);

            $post->setAttribute('is_liked',      $isLiked);
            $post->setAttribute('is_following',   $isFollowing);
            $post->setAttribute('reports_count',  $reports);
            $post->setAttribute('user_reported',  false);

            // ── Calculate recommendation score ───────────────────
            $score = 0;

            // 1. Following bonus (+50)
            if ($isFollowing) {
                $score += 50;
            }

            // 2. Location bonus (+30)
            $ngo = $ngoData->get($post->user_id);
            if ($userLocation && $ngo && stripos($ngo->registration_district, $userLocation) !== false) {
                $score += 30;
            }

            // 3. Explicit category preference (+20)
            if (!empty($userCategories) && $ngo && in_array($ngo->category, $userCategories)) {
                $score += 20;
            }

            // 4. Inferred category preference (+15)
            if (!empty($inferredCategories) && $ngo && in_array($ngo->category, $inferredCategories)) {
                $score += 15;
            }

            // 5. Favorited NGO bonus (+10)
            if ($isFavorited) {
                $score += 10;
            }

            // 6. Recency bonus (0–10, linear decay over 14 days)
            $ageInDays  = $post->created_at->diffInDays($now);
            $score     += max(0, 10 - (int)($ageInDays * 10 / 14));

            // 7. Report penalty (−5 per report)
            $score -= $reports * 5;

            $post->setAttribute('recommendation_score', $score);
        });

        // ── Sort by score desc, then created_at desc ─────────────
        $sortedModels = $this->mergeSort($filtered->all(), function ($a, $b) {
            $scoreDiff = $b->recommendation_score - $a->recommendation_score;
            if ($scoreDiff !== 0) {
                return $scoreDiff;
            }
            return strcmp($b->created_at, $a->created_at);
        });

        // ── Cap at max N per NGO ─────────────────────────────────
        $final    = [];
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

    /* --------------------------------------------------------------
     *  Infer category preferences from user behaviour
     *  (follows, likes, favorites)
     * ------------------------------------------------------------ */
    private function inferCategoryPreferences(
        int $userId,
        $followedNgoIds,
        $likedPostIds,
        $favoritedNgoIds
    ): array {
        // Gather all NGO user_ids the user has interacted with
        $interactedNgoUserIds = collect();

        // From follows
        $interactedNgoUserIds = $interactedNgoUserIds->merge($followedNgoIds->keys());

        // From favorites (ngo_id in favorites points to ngos.id, not user_id)
        $favNgoUserIds = \App\Models\Ngo::whereIn('id', $favoritedNgoIds->keys())
            ->pluck('user_id');
        $interactedNgoUserIds = $interactedNgoUserIds->merge($favNgoUserIds);

        // From liked posts → get the NGO user_ids
        $likedNgoUserIds = Post::whereIn('id', $likedPostIds->keys())
            ->pluck('user_id')
            ->unique();
        $interactedNgoUserIds = $interactedNgoUserIds->merge($likedNgoUserIds);

        // Get categories from those NGOs
        $categories = \App\Models\Ngo::whereIn('user_id', $interactedNgoUserIds->unique())
            ->whereNotNull('category')
            ->pluck('category')
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(5)   // top 5 inferred categories
            ->all();

        return $categories;
    }

    /* --------------------------------------------------------------
     *  Public feed endpoint
     * ------------------------------------------------------------ */
    public function index()
    {
        $posts = Post::with(['user.ngo', 'medias', 'likes', 'comments', 'milestone.event'])
            ->where('created_at', '>=', now()->subDays(60))
            ->latest('created_at')
            ->get();

        $sortedPosts = $this->sortPostsForFeed($posts, auth()->id(), 3);

        $milestones = [];
        if (auth()->check() && auth()->user()->isNgo()) {
            $milestones = \App\Models\EventMilestone::whereHas('event', function($q) {
                $q->where('user_id', auth()->id());
            })->where('status', '!=', 'completed')->get();
        }

        return view('common.feed.index', [
            'posts' => $sortedPosts,
            'milestones' => $milestones
        ]);
    }


    public function like(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $post = Post::findOrFail($request->post_id);
        $user = auth()->user();

        // Check if user has already liked the post
        $alreadyLiked = PostHasLikes::where('user_id', $user->id)->where('post_id', $post->id);

        if ($alreadyLiked->exists()) {
            $alreadyLiked = $alreadyLiked->first();
            $alreadyLiked->delete();
            ComputeUserRecommendations::dispatch($user->id)->afterCommit();
            // $isLiked = false;

            return response()->json([
                'message' => 'You already liked this post',
                'isLiked' => false,
            ], 400);
        }

        // Like the post
        PostHasLikes::create([
            'post_id' => $request->post_id,
            'user_id' => $user->id,
        ]);
        ComputeUserRecommendations::dispatch($user->id)->afterCommit();

        return response()->json([
            'message' => 'Liked the post successfully',
            'isLiked' => true,
        ], 201);
    }

    public function comment(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:post_has_comments,id',
        ]);

        $user = auth()->user();

        // Comment on the post
        $comment = PostHasComments::create([
            'comment' => $request->comment,
            'post_id' => $request->post_id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
        ]);
        ComputeUserRecommendations::dispatch($user->id)->afterCommit();

        $comments = PostHasComments::with(['user', 'replies.user'])->where('post_id', $request->post_id)->whereNull('parent_id')->get();
        return response()->json([
            'id' => $comment->id,
            'comments' => $comments,
        ], 201);
    }

    public function create(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:500', // Increased limit for better UX
            'post_media' => 'nullable|array',
            'post_media.*' => 'image|mimes:jpg,png,jpeg|max:5120', // Increased max size to 5MB
            'milestone_id' => 'nullable|exists:event_milestones,id',
        ]);

        // Custom validation: at least one of description or media must be present
        if (empty($request->description) && !$request->hasFile('post_media')) {
            return redirect()->route('common.feed')->withErrors(['description' => 'Either a description or at least one image is required.'])->withInput();
        }

        $user = auth()->user();

        $postData = [
            'description' => $request->description,
            'user_id' => $user->id,
            'type' => $request->hasFile('post_media') ? 'media' : 'text',
            'impressions' => 0,
            'milestone_id' => $request->milestone_id,
        ];

        try {
            $post = Post::create($postData);

            if ($request->hasFile('post_media')) {
                foreach ($request->file('post_media') as $mediaFile) {
                    $path = $mediaFile->store('posts', 'public');
                    Media::create([
                        'media_type' => 'image',
                        'media_path_name' => $path,
                        'post_id' => $post->id,
                    ]);
                }
            }

            return redirect()->route('common.feed')->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('common.feed')->withErrors(['error' => 'Failed to create post. Please try again.'])->withInput();
        }
    }

    public function report(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'report_description' => 'max:255',
            'reason' => 'required|string|max:255',
        ]);

        if ($request->reason == "other" && $request->report_description == NULL) {
            return response()->json(['message' => 'Pease write a report description'], 400);
        }

        $user = auth()->user();

        $alreadyReported = PostHasReports::where('user_id', $user->id)->where('post_id', $request->post_id);

        if ($alreadyReported->exists()) {
            return response()->json(['message' => 'You have already reported this Post'], 400);
        }

        PostHasReports::create([
            'report_description' => $request->report_description,
            'reason' => $request->reason,
            'post_id' => $request->post_id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('common.feed')->with('Error', 'Post reported successfully!');
    }

    public function follow(Request $request)
    {
        $request->validate([
            'ngo_id' => 'required|exists:users,id|different:user_id', // Ensure not self-follow
        ]);

        $userId = auth()->id(); // Follower is the logged-in user
        $ngoId = $request->ngo_id; // Followee from request

        // Toggle: Delete if exists, create if not
        $existing = Follows::where('user_id', $userId)
            ->where('ngo_id', $ngoId)
            ->first();

        if ($existing) {
            $existing->delete();
            $following = false;
        } else {
            Follows::create([
                'ngo_id' => $ngoId,
                'user_id' => $userId
            ]);
            $following = true;
        }
        ComputeUserRecommendations::dispatch($userId)->afterCommit();

        return response()->json(['following' => $following]);
    }

    // PostController.php
    public function undoReport(Post $post)
    {
        $post->reports()->where('user_id', auth()->id())->delete();
        return back()->with('success', 'Report removed.');
    }

    /* --------------------------------------------------------------
     *  AJAX: Update Location
     * ------------------------------------------------------------ */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'district' => 'required|string|max:100',
        ]);

        $user = auth()->user();
        
        // Update user location for persistent personalization
        $user->location = $request->district;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Location updated to ' . $request->district . '. Your feed is now optimized.',
            'district' => $request->district
        ]);
    }
}
