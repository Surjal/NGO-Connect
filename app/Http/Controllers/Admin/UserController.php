<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use App\Models\User;
use App\Notifications\NgoRegistrationApproved;
use App\Notifications\NgoRegistrationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
                'users' => $users->items(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'links' => $users->linkCollection()->toArray(),
                'total' => $users->total(),
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
        $user = User::where('role_id', 2)->with('ngo')->findOrFail($id);
        $user->update(['verified' => true]);
        $owner = User::find($user->ngo?->owner_id);
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

    public function index(Request $request)
    {
        $users = User::paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'phone'     => 'nullable|string|max:20',
            'role_id'   => 'required|integer|in:0,1,2',
            'profile_photo' => 'nullable|image|max:2048',
            'verified'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['verified'] = $request->has('verified');

        User::create($validated);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with('success', 'User deleted successfully!');
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
