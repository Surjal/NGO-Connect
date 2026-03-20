<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates 4 specific "People" users with predefined interests 
     * for testing the AI Recommendation Engine.
     */
    public function run(): void
    {
        $testUsers = [
            [
                'name' => 'John (Health Focus)',
                'email' => 'user1@people.com',
                'preferred_categories' => ['Health', 'Child Welfare'],
                'location' => 'Kathmandu',
            ],
            [
                'name' => 'Sarah (Environment Focus)',
                'email' => 'user2@people.com',
                'preferred_categories' => ['Environment', 'Disaster Relief', 'Agriculture'],
                'location' => 'Pokhara',
            ],
            [
                'name' => 'Mike (Education Focus)',
                'email' => 'user3@people.com',
                'preferred_categories' => ['Education', 'Technology'],
                'location' => 'Lalitpur',
            ],
            [
                'name' => 'Emma (Arts & Culture Focus)',
                'email' => 'user4@people.com',
                'preferred_categories' => ['Arts & Culture', 'Women Empowerment'],
                'location' => 'Bhaktapur',
            ],
        ];

        foreach ($testUsers as $userData) {
            // Check if user already exists to avoid duplicate seed errors
            if (!User::where('email', $userData['email'])->exists()) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'), // Easy password for testing
                    'role_id' => 2, // 2 = People role
                    'verified' => true,
                    'preferred_categories' => $userData['preferred_categories'], // The AI will use this!
                    'location' => $userData['location'],
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);
            }
        }

        $this->command->info('PeopleSeeder successfully completed: Created 4 test users.');
    }
}
