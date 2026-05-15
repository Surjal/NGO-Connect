<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Jobs\ComputeUserRecommendations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user->load([
            'followedNgos.user',
            'volunteeredEvents' => function($query) {
                $query->latest()->limit(5);
            },
            'donations' => function($query) {
                $query->with('ngo')->latest()->limit(5);
            },
            'certificates.event',
            'badges'
        ]);
        
        $stats = [
            'volunteering_count' => $user->volunteeredEvents()->wherePivot('status', 'accepted')->count(),
            'donations_count' => $user->donations()->count(),
            'total_donated' => $user->donations()->sum('donation_amount'),
        ];

        return view('people.profile.show', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = Auth::user();
    return view('people.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $preferredCategories = $request->preferred_categories ?? [];
        $preferredCategoriesChanged = $user->preferred_categories !== $preferredCategories;

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'location' => 'nullable|string|max:100',
            'preferred_categories' => 'nullable|array',
            'preferred_categories.*' => 'string|max:100',
        ]);

        $user->name = $request->name;
        $user->location = $request->location;
        $user->preferred_categories = $preferredCategories;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        if ($preferredCategoriesChanged) {
            ComputeUserRecommendations::dispatch($user->id)->afterCommit();
        }

        return redirect()->route('people.profile')->with('success', 'Profile updated successfully.');
    }
}
