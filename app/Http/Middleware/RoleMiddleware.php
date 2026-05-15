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
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();
        $roleMap = [
            'admin' => 0,
            'ngo' => 1,
            'people' => 2,
        ];

        $roles = array_map(function ($role) use ($roleMap) {
            if (is_numeric($role)) {
                return (int) $role;
            }

            return $roleMap[strtolower((string) $role)] ?? -1;
        }, $roles);

        if (in_array($user->role_id, $roles)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Unauthorized access.',
            ], 403);
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
