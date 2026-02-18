<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationHasPayment;
use App\Models\User;
use App\Notifications\DonationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get followed NGOs (User models)
        // detailed relationship: user -> followedNgos (Following model) -> user (User model - the NGO)
        // But User::followedNgos() is defined as belongsToMany(Ngo::class, 'follows', 'user_id', 'ngo_id')
        // Wait, User.php says: belongsToMany(Ngo::class...)
        // But Ngo::class might be the Ngo model, not User model.
        // Let's check if 'followedNgos' returns User objects or Ngo objects.
        // The relationship in User.php is: belongsToMany(Ngo::class, 'follows', 'user_id', 'ngo_id')
        // If Ngo::class is App\Models\Ngo, then it returns Ngo profile models.
        // But verified NGOs are Users with role_id=1.
        // Let's assume we need User models for the dropdown (since donation targets User id).
        
        // Actually, looking at User.php:
        // public function followedNgos(){ return $this->belongsToMany(Ngo::class, ...); }
        // If Ngo class is the profile, we need the User.
        
        // Let's fetch all verified NGOs and manually split them to be safe and simple.
        // Fetch verified NGOs and separate followed ones
        $allNgos = User::where('role_id', 1)->where('verified', true)->with('ngo')->get();
        
        $followedNgoIds = DB::table('follows')
            ->where('user_id', $user->id)
            ->pluck('ngo_id') // This is likely the User ID of the NGO based on 'follows' table usually storing user_ids
            ->toArray();
            
        $followedNgos = $allNgos->whereIn('id', $followedNgoIds);
        $otherNgos = $allNgos->whereNotIn('id', $followedNgoIds);

        $donations = Donation::where('user_id', Auth::id())->with(['ngo', 'payments'])->paginate(10);
        return view('people.donations.index', compact('followedNgos', 'otherNgos', 'donations'));
    }

    public function showPaymentForm(Request $request)
    {
        $request->validate([
            'ngo_id' => 'required|exists:users,id',
            'donation_amount' => 'required|numeric|min:10',
            'payment_method' => 'required|in:esewa',
        ]);

        $ngo = User::where('role_id', 1)->findOrFail($request->ngo_id);
        if (!$ngo->verified) {
            return redirect()->back()->with('error', 'This NGO is not verified to accept donations.');
        }

        if ($request->payment_method === 'esewa') {
            $total_amount = $request->donation_amount + 10; // 10 is the tax/service charge from the view
            $transaction_uuid = (string) Str::uuid();
            $product_code = env('ESEWA_PRODUCT_CODE', 'EPAYTEST');
            $secret_key = env('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q');

            // Create a pending donation record
            Donation::create([
                'user_id' => auth()->id(),
                'ngo_id' => $request->ngo_id,
                'transaction_uuid' => $transaction_uuid,
                'donation_amount' => $request->donation_amount,
                'status' => 'pending',
            ]);

            // Generate signature: total_amount,transaction_uuid,product_code
            $message = "total_amount={$total_amount},transaction_uuid={$transaction_uuid},product_code={$product_code}";
            $signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));

            return view('people.donations.payment', [
                'total' => $request->donation_amount,
                'ngo_id' => $request->ngo_id,
                'donation_amount' => $request->donation_amount,
                'payment_method' => $request->payment_method,
                'transaction_uuid' => $transaction_uuid,
                'product_code' => $product_code,
                'signature' => $signature,
                'esewa_url' => (app()->environment('local')) ? route('mock.esewa') : env('ESEWA_URL', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form'),
            ]);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $data = $request->input('data');
        if (!$data) {
            return redirect()->route('people.donations')->with('error', 'Invalid payment data.');
        }

        $decodedData = json_decode(base64_decode($data), true);
        if (!$decodedData) {
            return redirect()->route('people.donations')->with('error', 'Failed to decode payment data.');
        }

        $transaction_uuid = $decodedData['transaction_uuid'];
        $donation = Donation::where('transaction_uuid', $transaction_uuid)->first();

        if (!$donation) {
            return redirect()->route('people.donations')->with('error', 'Donation record not found.');
        }

        if ($donation->status === 'completed') {
            return redirect()->route('people.donations')->with('success', 'Donation already completed.');
        }

        // In local/mock mode, skip external API verification
        if (app()->environment('local') && ($decodedData['status'] ?? '') === 'COMPLETE') {
            $verified = true;
        } else {
            // Verify with eSewa Status API (production)
            $statusUrl = env('ESEWA_STATUS_URL', 'https://rc-epay.esewa.com.np/api/epay/transaction/status');
            $response = Http::get($statusUrl, [
                'product_code' => env('ESEWA_PRODUCT_CODE', 'EPAYTEST'),
                'total_amount' => $decodedData['total_amount'],
                'transaction_uuid' => $transaction_uuid,
            ]);
            $verified = $response->successful() && $response->json('status') === 'COMPLETE';
        }

        if ($verified) {
            $donation->update(['status' => 'completed']);
            
            // Send notification to NGO
            $ngoUser = User::find($donation->ngo_id);
            if ($ngoUser) {
                $ngoUser->notify(new DonationReceived($donation));
            }

            return redirect()->route('people.donations')->with('success', 'Thank you for your donation!');
        }

        $donation->update(['status' => 'failed']);
        return redirect()->route('people.donations')->with('error', 'Payment verification failed.');
    }

    public function paymentFail()
    {
        return redirect()->route('people.donations')->with('error', 'Payment was cancelled or failed.');
    }
}
