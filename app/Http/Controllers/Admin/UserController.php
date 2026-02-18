<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use App\Models\User;
use App\Notifications\NgoRegistrationApproved;
use App\Notifications\NgoRegistrationRejected;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:admin']);
    }

    public function showuser(Request $request)
    {
        $query = User::where('role_id', 2);

        // ---------- SEARCH BY NAME ----------
        if ($request->filled('name')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->name) . '%']);
        }

        $users = $query->paginate(10)->appends($request->all());

        // ---------- JSON RESPONSE FOR AJAX ----------
        if ($request->has('user')) {
            return response()->json([
                'users'        => $users->items(),
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'links'        => $users->linkCollection()->toArray(),
                'total'        => $users->total(),
            ]);
        }

        return view('admin.user.list', compact('users'));
    }

    /**
     * Show individual user detail page.
     */
    public function show($id)
    {
        $user = User::where('role_id', 2)->with('ngo')->findOrFail($id);
        return view('admin.user.show', compact('user'));
    }

    /**
     * Verify user (approve).
     */
    public function verify(Request $request, $id)
    {
        $user = User::where('role_id', 2)->findOrFail($id);
        $user->update(['verified' => true]);
        $owner = User::find($user->ngo->owner_id ?? null);
        $owner?->update(['verified' => true]);

        $user->notify(new NgoRegistrationApproved($user->name, true));
        if ($owner) {
            $owner->notify(new NgoRegistrationApproved($user->name, false));
        }

        return redirect()->route('admin.users')->with('success', 'User verified successfully.');
    }

    /**
     * Reject user registration.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $user = User::where('role_id', 2)->findOrFail($id);
        $user->notify(new NgoRegistrationRejected($request->rejection_reason));
        $user->delete();
        if ($user->ngo && $user->ngo->owner_id) {
            User::find($user->ngo->owner_id)?->delete();
        }

        return response()->json(['message' => 'User registration rejected and deleted.']);
    }

    /**
     * Suspend / Unsuspend NGO.
     */
    public function suspend(Request $request, $id)
    {
        $user = User::where('role_id', 2)->findOrFail($id);

        if ($request->type == 'suspend') {
            $user->update([
                'suspended' => true,
                'suspension_reason' => $request->suspension_reason,
                'suspended_at' => now(),
            ]);

            return redirect()->route('admin.user.show', ['id' => $id])->with('success', 'User suspended successfully.');
        } elseif ($request->type == 'unsuspend') {
            $user->update([
                'suspended' => false,
                'suspension_reason' => null,
                'suspended_at' => null,
            ]);

            return redirect()->route('admin.user.show', ['id' => $id])->with('success', 'User unsuspended successfully.');
        }
    }
}
