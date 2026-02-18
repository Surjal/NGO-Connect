<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NgoSeeder extends Seeder
{
    public function run(): void
    {
        $ngoUsers = User::where('role_id', 1)->get();

        $categories = ['Education', 'Health', 'Environment', 'Women', 'Children', 'Animals', 'Disaster', 'Youth'];
        $subcategories = [
            'Child Education',
            'Community Health',
            'Reforestation',
            'Women Rights',
            'Orphan Care',
            'Animal Rescue',
            'Flood Relief',
            'Skill Training'
        ];

        foreach ($ngoUsers as $index => $user) {
            DB::table('ngos')->insert([
                'user_id' => $user->id,
                'ngo_name' => $user->name,
                'registration_date' => Carbon::now()->subYears(5)->addDays(rand(0, 1500)),
                'category' => $categories[$index % count($categories)],
                'subcategory' => $subcategories[$index % count($subcategories)],
                'address' => $this->fakeAddress(),
                'phone' => '+977-98' . rand(1000000, 9999999),
                'mission' => $this->fakeMission(),
                'registration_number' => 'DAO-' . rand(10000, 99999),
                'registration_district' => $this->fakeDistrict(),
                'last_renewal_date' => Carbon::now()->subMonths(rand(1, 12)),
                'pan_number' => 'PAN' . rand(1000000, 9999999),
                'description' => $this->fakeDescription(),
                'contact_position' => ['Director', 'Manager', 'Coordinator'][rand(0, 2)],
                'logo' => NULL,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    private function fakeAddress()
    {
        $areas = ['Kathmandu', 'Pokhara', 'Biratnagar', 'Lalitpur', 'Bharatpur', 'Birgunj', 'Janakpur'];
        return $areas[array_rand($areas)] . ', Nepal';
    }

    private function fakeDistrict()
    {
        $districts = ['Kathmandu', 'Kaski', 'Morang', 'Lalitpur', 'Chitwan', 'Parsa', 'Dhanusa'];
        return $districts[array_rand($districts)];
    }

    private function fakeMission()
    {
        $missions = [
            'Empowering rural education.',
            'Health for all.',
            'Protecting the environment.',
            'Women’s rights and safety.',
            'Supporting underprivileged children.',
            'Animal welfare and rescue.',
            'Disaster response and recovery.'
        ];
        return $missions[array_rand($missions)];
    }

    private function fakeDescription()
    {
        return "We are a dedicated NGO working since 2010 to improve lives through " .
            ['education', 'healthcare', 'environment', 'empowerment'][rand(0, 3)] .
            " in rural Nepal.";
    }
}
