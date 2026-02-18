<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Admin
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@ngo.com',
            'password' => bcrypt('password'),
        ]);

        // 30 NGOs
        $ngoNames = [
            'Nepal Education Foundation',
            'Healthy Nepal Initiative',
            'Green Himalaya',
            'Child Welfare Nepal',
            'Women Empowerment Nepal',
            'Clean Water Nepal',
            'Rural Health Nepal',
            'Youth Nepal',
            'Animal Rescue Nepal',
            'Disaster Relief Nepal',
            'Senior Care Nepal',
            'Digital Nepal',
            'Art & Culture Nepal',
            'Sports for All',
            'Mental Health Nepal',
            'Tech for Good',
            'Food Bank Nepal',
            'Reforest Nepal',
            'Skill Nepal',
            'Ocean Nepal',
            'Heritage Nepal',
            'Startup Nepal',
            'Solar Nepal',
            'Farmers Nepal',
            'Books for All',
            'Music Nepal',
            'Dance Nepal',
            'Film Nepal',
            'Science Nepal',
            'Math Nepal'
        ];

        foreach ($ngoNames as $i => $name) {
            User::factory()->ngo()->create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '', $name)) . "@ngo.com",
            ]);
        }

        // 5 People (regular users) with sample location and preferences
        $samplePeople = [
            ['location' => 'Kathmandu', 'preferred_categories' => json_encode(['Education', 'Health'])],
            ['location' => 'Lalitpur', 'preferred_categories' => json_encode(['Environment', 'Child Welfare'])],
            ['location' => 'Bhaktapur', 'preferred_categories' => json_encode(['Women Empowerment', 'Arts & Culture'])],
            ['location' => 'Chitwan', 'preferred_categories' => json_encode(['Animal Welfare', 'Agriculture'])],
            ['location' => 'Kaski', 'preferred_categories' => json_encode(['Disaster Relief', 'Community Development'])],
        ];

        foreach ($samplePeople as $data) {
            User::factory()->people()->create($data);
        }
    }
}
