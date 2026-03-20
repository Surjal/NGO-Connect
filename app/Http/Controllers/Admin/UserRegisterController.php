<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:admin']);
    }

    /**
     * Show form for creating a new user (index page)
     */
    public function showuser(Request $request)
    {
        // Optional: show existing users if needed
        $users = User::paginate(10);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Store new user in database
     */
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

        // Encrypt password
        $validated['password'] = Hash::make($validated['password']);

        // Convert checkbox to boolean
        $validated['verified'] = $request->has('verified');

        // Create user
        User::create($validated);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    /**
     * Show a specific user (details page)
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.show', compact('user'));
    }

    /**
     * Delete a user (optional)
     */
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
}
