<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostHasReportsSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        $people = User::where('role_id', 2)->get();
        $reasons = ['Spam', 'Inappropriate', 'Fake News', 'Harassment', 'Other'];

        $reportCount = 0;
        while ($reportCount < 40) {
            $post = $posts->random();
            $reporter = $people->random();

            // Avoid duplicate
            $exists = DB::table('post_has_reports')
                ->where('post_id', $post->id)
                ->where('user_id', $reporter->id)
                ->exists();

            if (!$exists) {
                DB::table('post_has_reports')->insert([
                    'post_id' => $post->id,
                    'user_id' => $reporter->id,
                    'reason' => $reasons[array_rand($reasons)],
                    'report_description' => 'This post seems suspicious.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $reportCount++;
            }
        }
    }
}
