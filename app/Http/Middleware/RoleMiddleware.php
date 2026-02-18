<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();
        $roles = array_map('intval', $roles);

        if (in_array($user->role_id, $roles)) {
            return $next($request);
        }
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
        } elseif ($user->isNgo()) {
            return redirect()->route('common.feed')->with('error', 'Unauthorized access.');
        } else {
            return redirect()->route('common.feed')->with('error', 'Unauthorized access.');
        }
    }
}
