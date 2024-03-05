<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is a cashier role
        if (Auth::check() && Auth::user()->role == 3) {
            return $next($request);
        }

        // Redirect to admin dashboard
        return redirect('/admin');
    }
}