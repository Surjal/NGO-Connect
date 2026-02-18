<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ngo;
use App\Models\PostHasReports;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NgoRegistrationApproved;
use App\Notifications\NgoRegistrationRejected;

class AdminController extends Controller
{
    /* public function showNgos(){
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
        */

    /* public function showNgos(){
        $ngos = User::where('role_id', 1)->with('ngo')->paginate(1); //change it to 10
        return view('admin.ngos.list', compact('ngos'));
    }

    // public function ngos()
    // {
    //     $ngos = User::where('role_id', 1)->with('ngo')->paginate(10);
    //     return view('admin.ngos.index', compact('ngos'));
    // }

    public function show($id)
    {
        $ngo = User::where('role_id', 1)->with('ngo')->findOrFail($id);
        return view('admin.ngos.show', compact('ngo'));
    }

    public function verifyNgo(Request $request, $id)
    {
        $ngo = User::where('role_id', 1)->findOrFail($id);
        $ngo->update(['verified' => true]);
        $owner = User::find($ngo->owner_id);
        $owner->update(['verified'=>true]);

        // Notify the NGO
        $ngo->notify(new NgoRegistrationApproved($ngo->name, true));

        // Notify the owner if exists
        if ($ngo->owner_id) {
            if ($owner) {
                $owner->notify(new NgoRegistrationApproved($ngo->name, false));
            }
        }

        return redirect()->route('admin.ngos')->with('success', 'NGO verified successfully.');
    }

    public function rejectNgo(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $ngo = User::where('role_id', 1)->findOrFail($id);
        $ngo->notify(new NgoRegistrationRejected($request->rejection_reason));
        $ngo->delete();
        return redirect()->route('admin.ngos')->with('success', 'NGO registration rejected and deleted.');
    }
        */
}
