<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{   
    public function switchToNgo(Request $request, $ngoId)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isPeople()) {
            return redirect()->route('people.feed')->with('error', 'Only people can switch to NGO accounts.');
        }

        $ngo = $currentUser->ownedNgos()->findOrFail($ngoId);

        // Store the current user's ID for switching back
        $request->session()->put('original_user_id', $currentUser->id);

        // Log out the current user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log in as the NGO user
        Auth::login($ngo);

        return redirect()->route('common.feed')->with('success', 'Switched to NGO account.');
    }

    public function switchBack(Request $request)
    {
        $originalUserId = $request->session()->get('original_user_id');

        if (!$originalUserId) {
            return redirect()->route('login')->with('error', 'No original user found to switch back.');
        }

        $originalUser = User::findOrFail($originalUserId);

        // Log out the current (NGO) user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log in as the original user
        Auth::login($originalUser);

        // Clear the original_user_id from session
        $request->session()->forget('original_user_id');

        return redirect()->route('common.feed')->with('success', 'Switched back to your personal account.');
    }
}
