<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Default password for seeding
            'role_id' => 2, // Default to people
            'owner_id' => null,
            'verified' => false,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->state([
            'role_id' => 0,
            'verified' => true,
        ]);
    }

    public function ngo()
    {
        return $this->state([
            'role_id' => 1,
            'verified' => false,
        ]);
    }

    public function people()
    {
        return $this->state([
            'role_id' => 2,
            'verified' => true,
        ]);
    }
}
