<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ngo;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;
use App\Models\PostHasReports;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:admin']);
    }

    public function dashboard(){
        $ngoCount = Ngo::count();
        $userCount = User::count();
        $pendingNgoApprovals = User::where('role_id',1)->where('verified',0)->count();
        $reportedPosts = PostHasReports::count();
        $totalDonations = Donation::sum('donation_amount');
        return view('admin.dashboard',compact('ngoCount','userCount','pendingNgoApprovals','reportedPosts','totalDonations'));
    }
}
