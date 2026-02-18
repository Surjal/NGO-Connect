<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsSeeder extends Seeder
{
    public function run()
    {
        $ngoUsers = \App\Models\User::where('role_id', 1)->get();
        
        $eventTitles = [
            'Charity Fundraiser Gala', 'Community Clean-up Drive', 'Free Health Camp', 
            'Tech for Good Workshop', 'Youth Leadership Summit', 'Environmental Awareness Rally',
            'Blood Donation Camp', 'Educational Book Fair', 'Winter Clothes Distribution',
            'Women Empowerment Seminar', 'Animal Shelter Open Day', 'Digital Literacy Class',
            'Rural Development Meeting', 'Disaster Relief Training', 'Art Competition for Kids'
        ];
        
        $locations = [
            'Kathmandu, Nepal', 'Lalitpur, Nepal', 'Bhaktapur, Nepal', 'Pokhara, Nepal', 
            'Chitwan, Nepal', 'Biratnagar, Nepal', 'Online (Zoom)', 'Local Community Center'
        ];

        foreach ($ngoUsers as $ngo) {
            // detailed loop to create 1-3 events per NGO
            $eventCount = rand(1, 3);
            
            for ($i = 0; $i < $eventCount; $i++) {
                $type = rand(0, 1); // 0: Online, 1: Offline
                $location = $type == 0 ? 'Online (Zoom)' : $locations[array_rand($locations)];
                
                $startDate = Carbon::now()->addDays(rand(1, 60));
                $endDate = (clone $startDate)->addHours(rand(2, 6));

                DB::table('events')->insert([
                    'title' => $eventTitles[array_rand($eventTitles)],
                    'description' => 'Join us for this impactful event where we aim to make a difference in our community.',
                    'requirements' => 'Open to all. Please register in advance.',
                    'location' => $location,
                    'type' => (string)$type,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'capacity' => (string)rand(50, 500),
                    'is_volunteers_required' => (bool)rand(0, 1),
                    'user_id' => $ngo->id,
                    'cover_image_path_name' => null, // Or add a placeholder if available
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
