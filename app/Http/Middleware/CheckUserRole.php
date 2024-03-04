<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckUserRole
{
    public function handle($request, Closure $next, $role)
    {
        $user = $request->user();
        if ($user && $user->role == $role) {
            return $next($request);
        }

        Log::info('Access Denied: User Role - ' . ($user ? $user->role : 'Guest'));

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}