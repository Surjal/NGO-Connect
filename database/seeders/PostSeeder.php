<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $ngoUsers = User::where('role_id', 1)->get();
        $postTexts = [
            "Distributed food to 200 families today!",
            "Planted 500 trees in Pokhara!",
            "Free health camp served 300 patients.",
            "Scholarships awarded to 50 students.",
            "New computer lab opened in remote school.",
            "Rescued 12 street dogs this week.",
            "Winter clothes distributed in mountain villages.",
            "Mental health workshop held.",
            "Solar panels installed in 5 schools.",
            "Women trained in digital skills.",
            "Flood victims received emergency kits.",
            "Art exhibition raised 50K for charity.",
            "Youth leadership camp completed.",
            "Clean water project launched.",
            "Books donated to 10 libraries.",
            "Music therapy for seniors started."
        ];

        foreach ($ngoUsers as $ngo) {
            $postCount = rand(3, 5); // Increased to ensure content
            for ($i = 0; $i < $postCount; $i++) {
                $type = rand(0, 10) < 7 ? 'media' : 'text'; // 70% chance of media

                /** @var Post $post */
                $post = Post::create([
                    'description' => $postTexts[array_rand($postTexts)],
                    'type' => $type,
                    'impressions' => rand(10, 500),
                    'user_id' => $ngo->id,
                    'created_at' => Carbon::now()->subDays(rand(0, 60)), // Match controller logic
                    'updated_at' => Carbon::now(),
                ]);

                if ($type === 'media') {
                    // Randomly decide image or video
                    // 80% image, 20% video
                    $mediaType = rand(0, 10) < 8 ? 'image' : 'video';

                    if ($mediaType === 'image') {
                        $count = rand(1, 3);
                        for ($j = 0; $j < $count; $j++) {
                            \App\Models\Media::create([
                                'media_type' => 'image',
                                'media_path_name' => 'posts/sample' . rand(1, 2) . '.png',
                                'post_id' => $post->id,
                            ]);
                        }
                    } else {
                        \App\Models\Media::create([
                            'media_type' => 'video',
                            'media_path_name' => 'posts/sample_video.mp4',
                            'post_id' => $post->id,
                        ]);
                    }
                }
            }
        }
    }
}
