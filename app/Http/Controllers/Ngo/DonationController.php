<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User;
use App\Notifications\DonationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function donations()
    {
        $donations = Donation::where('ngo_id', Auth::id())->with(['user', 'payments'])->paginate(10);
        return view('ngo.donations.index', compact('donations'));
    }

    public function verifyDonation(Request $request, $donationId)
    {
        $donation = Donation::where('ngo_id', Auth::id())->findOrFail($donationId);
        $payment = $donation->payments()->first();

        if ($donation->status !== 'pending' || !in_array($payment->payment_method, ['cash', 'cheque'])) {
            return redirect()->route('ngo.donations')->with('error', 'Only pending cash or cheque donations can be verified.');
        }

        DB::transaction(function () use ($donation, $payment) {
            $donation->update(['status' => 'completed']);

            $existingResponse = $payment->payment_response;

            if (is_string($existingResponse)) {
                $existingResponse = json_decode($existingResponse, true) ?? [];
            }

            $payment->update([
                'status' => 'success',
                'payment_response' => json_encode(array_merge(
                    $existingResponse,
                    ['verified_at' => now()]
                )),
            ]);

            $ngo = User::find($donation->user_id);
            $ngo->notify(new DonationReceived($donation));
        });

        // return redirect()->route('ngo.donations')->with('success', 'Donation verified successfully.');//change this
        return redirect()->view('people.donations');//change this
    }
}
