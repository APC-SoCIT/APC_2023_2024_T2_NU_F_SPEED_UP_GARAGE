<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {   
        // Check if the user is authenticated and is a cashier
        if (Auth::check() && Auth::user()->role == 1) {
            return $next($request);
        }

        // Redirect to unauthorized page or homepage
        return redirect('/admin'); // Adjust as per your application's logic
    }
}
