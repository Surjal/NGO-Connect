<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function donations()
    {
        $donations = Donation::where('ngo_id', Auth::id())->with('user')->latest()->paginate(10);

        return view('ngo.donations.index', compact('donations'));
    }
}
