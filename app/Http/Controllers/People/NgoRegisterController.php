<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use App\Models\User;
use App\Notifications\AdminNgoRegistration;
use App\Notifications\NgoRegistrationPending;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NgoRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('people.ngo.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'mission' => 'required|string|max:1000',
            'description' => 'required|string|max:2000',
            'location' => 'required|string|max:255',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required|in:education,health,environment,social',
            'sub_categories' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photos[] = $photo->store('ngo_photos', 'public');
                }
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 1, // NGO role
                'owner_id' => Auth::check() ? Auth::id() : null,
                'verified' => false,
            ]);

            Ngo::create([
                'user_id' => $user->id,
                'mission' => $request->mission,
                'description' => $request->description,
                'location' => $request->location,
                'photos' => $photos,
                'category' => $request->category,
                'sub_categories' => $request->sub_categories,
            ]);

            // Trigger email verification
            event(new Registered($user));

            // Notify user of pending registration
            $user->notify(new NgoRegistrationPending());

            // Notify admins
            $admins = User::where('role_id', 0)->get(); // Admin role
            foreach ($admins as $admin) {
                $admin->notify(new AdminNgoRegistration($user));
            }
        });

        return redirect()
            ->route('login')
            ->with('success', 'NGO registration submitted. Please verify your email and await admin approval.');
    }
}
