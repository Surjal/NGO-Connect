<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\DonationHasPayment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ngos = User::where('role_id', 1)->get();
        $people = User::where('role_id', 2)->get();

        if ($ngos->isEmpty() || $people->isEmpty()) {
            return;
        }

        foreach ($people as $person) {
            // Each person makes 1-3 donations
            $donationCount = rand(1, 3);
            
            for ($i = 0; $i < $donationCount; $i++) {
                $ngo = $ngos->random();
                $amount = rand(50, 5000);
                $status = collect(['completed', 'completed', 'pending', 'failed'])->random();

                $donation = Donation::create([
                    'user_id' => $person->id,
                    'ngo_id' => $ngo->id,
                    'donation_amount' => $amount,
                    'status' => $status,
                ]);

                if ($status === 'completed') {
                    DonationHasPayment::create([
                        'donation_id' => $donation->id,
                        'payment_method' => 'esewa',
                        'payment_response' => [
                            'transaction_id' => 'TEST_' . uniqid(),
                            'amount' => $amount,
                            'status' => 'success',
                        ],
                        'status' => 'success',
                    ]);
                }
            }
        }
    }
}
