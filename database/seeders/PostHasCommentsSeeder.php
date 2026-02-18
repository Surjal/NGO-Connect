<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostHasCommentsSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        $people = User::where('role_id', 2)->get();
        $comments = [
            "Great work!",
            "Keep it up!",
            "How can I help?",
            "Amazing initiative!",
            "This is needed.",
            "Proud to support!",
            "Well done team!",
            "Inspiring!",
            "Can I volunteer?",
            "More power to you!",
            "Thank you for this."
        ];

        foreach ($posts as $post) {
            $commentCount = rand(0, 8);
            $commenters = $people->random(min($commentCount, $people->count()));

            foreach ($commenters as $user) {
                DB::table('post_has_comments')->insert([
                    'comment' => $comments[array_rand($comments)],
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                    'parent_id' => null,
                    'created_at' => now()->subMinutes(rand(1, 1440)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
