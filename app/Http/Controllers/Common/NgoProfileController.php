<?php

namespace App\Http\Controllers\Common;

use App\Models\Post;
use App\Models\User;
use App\Models\Event;
use App\Models\Follows;
use App\Models\PostHasLikes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NgoProfileController extends Controller
{
    public function index($id){

    $ngo = User::where('role_id', 1)->with('ngo')->findOrFail($id);
    $posts = Post::where('user_id',$id)->orderBy('created_at', 'desc')->get(); // Post::paginate(20)
    $eventsCount = Event::where('user_id',$id)->count();
    $followersCount = Follows::where('ngo_id',$id)->count();

    $isFollowing = false;

    if (auth()->check()) {
        $userId = auth()->id();
        $isFollowing = Follows::where('user_id', $userId)->where('ngo_id', $id)->exists();

        $userLikedPostIds = PostHasLikes::where('user_id', $userId)->pluck('post_id')->toArray();
        $posts->each(function ($post) use ($userLikedPostIds) {
            $post->is_liked = in_array($post->id, $userLikedPostIds);
        });

    } else {
        // Guests: No likes or follows
        $posts->each(function ($post) {
            $post->is_liked = false;
        });
    }
        return view('common.profile.ngo', compact('posts','ngo','eventsCount','followersCount','isFollowing'));
    }
}
