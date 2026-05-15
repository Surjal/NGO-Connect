<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Follows;
use App\Models\Post;
use App\Services\ChurnPredictionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(ChurnPredictionService $churnPredictionService)
    {
        $ngoUserId = Auth::id();

        // Stats
        $totalEvents = Event::where('user_id', $ngoUserId)->count();
        $totalFollowers = Follows::where('ngo_id', $ngoUserId)->count();
        $totalDonations = Donation::where('ngo_id', $ngoUserId)
            ->sum('donation_amount');
            
        $totalVolunteers = DB::table('event_has_volunteers')
            ->join('events', 'event_has_volunteers.event_id', '=', 'events.id')
            ->where('events.user_id', $ngoUserId)
            ->count();

        // Recent Data
        $upcomingEvents = Event::where('user_id', $ngoUserId)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        $recentDonations = Donation::with('user')
            ->where('ngo_id', $ngoUserId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentPosts = Post::where('user_id', $ngoUserId)
            ->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $milestones = \App\Models\EventMilestone::whereHas('event', function($q) use ($ngoUserId) {
            $q->where('user_id', $ngoUserId);
        })->where('status', '!=', 'completed')->get();

        $churnData = $churnPredictionService->ensureDashboardPredictionsForNgo($ngoUserId);

        return view('ngo.dashboard', compact(
            'totalEvents', 
            'totalFollowers', 
            'totalDonations', 
            'totalVolunteers',
            'upcomingEvents',
            'recentDonations',
            'recentPosts',
            'milestones',
            'churnData'
        ));
    }
}
