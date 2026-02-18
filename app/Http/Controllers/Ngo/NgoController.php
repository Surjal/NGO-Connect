<?php

namespace App\Http\Controllers\Ngo;

use App\Models\Ngo;
use App\Models\User;
use App\Models\Follows;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NgoController extends Controller
{
    public function show()
    {
        return redirect()->route('common.ngo.profile', Auth::id());
    }

    public function edit()
    {
        $ngo = Auth::user()->ngo;
        return view('ngo.profile.edit', compact('ngo'));
    }

    // This needs to be removed
    public function update(Request $request)
    {
        $user = Auth::user();
        $ngo = $user->ngo ?? new Ngo(['user_id' => $user->id]);

        $request->validate([
            'name' => 'required|string|max:255',
            'mission' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'sub_categories' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = User::find($user->id)
            ->fill($request->only(['name']));
        $ngo->fill($request->only(['mission', 'description', 'location', 'category', 'sub_categories']));

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('ngo_logos', 'public');
            $ngo->logo = $path;
        }

        $ngo->save();
        $user->save();
        // Associate the NGO with the user if not already linked
        if (!$user->ngo) {
            $user->ngo()->save($ngo);
        }

        return redirect()->route('common.ngo.profile', $user->id)->with('success', 'NGO profile updated successfully.');
    }

    public function showFollowers()
    {
        $ngoId = auth()->user('ngo')->id;
        // get follow rows with user
        $follows = Follows::where('ngo_id', $ngoId)->with('user')->get();

        // map to users
        $followers = $follows->map->user->filter();
        // dd($followers);
        return view('ngo.followers',compact('followers'));

    }

    public function volunteers()
    {
        // Placeholder for volunteers listing
        return view('ngo.volunteers.index');
    }

    public function donations()
    {
        // Placeholder for donations listing
        return view('ngo.donations.index');
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('ngo.notifications.index', compact('notifications'));
    }
}
