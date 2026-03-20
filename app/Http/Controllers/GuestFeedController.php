<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Common\FeedController;
use App\Models\Post;
use Illuminate\Http\Request;

class GuestFeedController extends Controller
{
    protected $feedController;

    public function __construct(FeedController $feedController)
    {
        $this->feedController = $feedController;
    }

    public function index()
    {
        $posts = Post::with(['user.ngo'])
            ->where('created_at', '>=', now()->subDays(60))
            ->latest('created_at')
            ->get();

        $sortedPosts = $this->feedController->sortPostsForFeed($posts, null, 3);

        return view('guest-feed', ['posts' => $sortedPosts]);
    }
}
