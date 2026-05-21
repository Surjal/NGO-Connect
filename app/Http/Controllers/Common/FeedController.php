<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests\StorePostRequest;
use App\Jobs\ComputeUserRecommendations;
use App\Models\Post;
use App\Models\Media;
use App\Models\Follows;
use App\Models\PostHasLikes;
use App\Models\PostHasComments;
use App\Models\PostHasReports;
use App\Models\EventMilestone;
use App\Services\FeedService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedController extends Controller
{
    public function __construct(
        private readonly FeedService $feedService
    ) {}

    public function index()
    {
        $posts = Post::with(['user.ngo', 'medias', 'likes', 'comments', 'milestone.event'])
            ->where('created_at', '>=', now()->subDays(60))
            ->latest('created_at')
            ->get();

        $sortedPosts = $this->feedService->sortPostsForFeed($posts, auth()->id(), 3);

        $milestones = [];
        if (auth()->check() && auth()->user()->isNgo()) {
            $milestones = EventMilestone::whereHas('event', function ($q) {
                $q->where('user_id', auth()->id());
            })->where('status', '!=', 'completed')->get();
        }

        return view('common.feed.index', [
            'posts' => $sortedPosts,
            'milestones' => $milestones,
        ]);
    }

    public function like(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $post = Post::findOrFail($request->post_id);
        $user = auth()->user();

        $alreadyLiked = PostHasLikes::where('user_id', $user->id)->where('post_id', $post->id);

        if ($alreadyLiked->exists()) {
            $alreadyLiked->first()->delete();
            ComputeUserRecommendations::dispatch($user->id)->afterCommit();

            return response()->json([
                'message' => 'You already liked this post',
                'isLiked' => false,
            ], 400);
        }

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

        $comment = PostHasComments::create([
            'comment' => $request->comment,
            'post_id' => $request->post_id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
        ]);
        ComputeUserRecommendations::dispatch($user->id)->afterCommit();

        $comments = PostHasComments::with(['user', 'replies.user'])
            ->where('post_id', $request->post_id)
            ->whereNull('parent_id')
            ->get();

        return response()->json([
            'id' => $comment->id,
            'comments' => $comments,
        ], 201);
    }

    public function create(StorePostRequest $request)
    {
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

        if ($request->reason == 'other' && $request->report_description == null) {
            return response()->json(['message' => 'Pease write a report description'], 400);
        }

        $user = auth()->user();

        $alreadyReported = PostHasReports::where('user_id', $user->id)
            ->where('post_id', $request->post_id);

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
            'ngo_id' => 'required|exists:users,id|different:user_id',
        ]);

        $userId = auth()->id();
        $ngoId = $request->ngo_id;

        $existing = Follows::where('user_id', $userId)
            ->where('ngo_id', $ngoId)
            ->first();

        if ($existing) {
            $existing->delete();
            $following = false;
        } else {
            Follows::create([
                'ngo_id' => $ngoId,
                'user_id' => $userId,
            ]);
            $following = true;
        }
        ComputeUserRecommendations::dispatch($userId)->afterCommit();

        return response()->json(['following' => $following]);
    }

    public function undoReport(Post $post)
    {
        $post->reports()->where('user_id', auth()->id())->delete();
        return back()->with('success', 'Report removed.');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'district' => 'required|string|max:100',
        ]);

        $user = auth()->user();

        $user->location = $request->district;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Location updated to ' . $request->district . '. Your feed is now optimized.',
            'district' => $request->district,
        ]);
    }
}
