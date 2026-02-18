<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NgoProfileController extends Controller
{
    public function show($id)
    {
        $ngo = Ngo::findOrFail($id);
        return view('people.ngo.profile', compact('ngo'));
    }

    public function toggleFavorite(Request $request, $id)
    {
        $user = Auth::user();
        $ngo = Ngo::findOrFail($id);

        if ($user->favoriteNgos()->where('ngo_id', $ngo->id)->exists()) {
            $user->favoriteNgos()->detach($ngo->id);
            $message = 'NGO removed from favorites.';
        } else {
            $user->favoriteNgos()->attach($ngo->id);
            $message = 'NGO added to favorites.';
        }

        return redirect()->back()->with('success', $message);
    }
}
