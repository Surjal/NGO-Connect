<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only check verified status for NGOs (role_id 1) or other roles, not Volunteers/People (role_id 2)
        if ($user && $user->verified == 0 && $user->role_id != 2) {
            return response()->json([
                'message' => 'Your account has been suspended or is under review. Please contact support.'
            ], 403);
        }
        return $next($request);
    }
}
