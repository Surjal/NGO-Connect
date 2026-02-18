<?php

namespace App\Http\Controllers\Ngo;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function deletePost($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }

        if ($post->delete()) {
            return redirect()->back()->with('success', 'Post deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Post could not be deleted.');
        }
    }



}
