<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is an inventory role
        if (Auth::check() && Auth::user()->role == 2) {
            return $next($request);
        }

        // Redirect to admin dashboard
        return redirect('/admin');
    }
}
