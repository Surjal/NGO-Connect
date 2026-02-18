<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostHasLikesSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        $people = User::where('role_id', 2)->get();

        foreach ($posts as $post) {
            $likeCount = rand(0, 15);
            $likers = $people->random(min($likeCount, $people->count()));

            foreach ($likers as $user) {
                DB::table('post_has_likes')->updateOrInsert(
                    ['post_id' => $post->id, 'user_id' => $user->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
