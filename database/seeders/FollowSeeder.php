<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowSeeder extends Seeder
{
    public function run(): void
    {
        $people = User::where('role_id', 2)->get();
        $ngos = User::where('role_id', 1)->get();

        foreach ($people as $person) {
            $followCount = rand(5, 20);
            $followed = $ngos->random(min($followCount, $ngos->count()))->pluck('id');

            foreach ($followed as $ngoId) {
                DB::table('follows')->insert([
                    'user_id' => $person->id,
                    'ngo_id' => $ngoId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
