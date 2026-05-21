<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\FeedService;

class GuestFeedController extends Controller
{
    public function __construct(
        private readonly FeedService $feedService
    ) {}

    public function index()
    {
        $posts = Post::with(['user.ngo'])
            ->where('created_at', '>=', now()->subDays(60))
            ->latest('created_at')
            ->get();

        $sortedPosts = $this->feedService->sortPostsForFeed($posts, null, 3);

        return view('guest-feed', ['posts' => $sortedPosts]);
    }
}
