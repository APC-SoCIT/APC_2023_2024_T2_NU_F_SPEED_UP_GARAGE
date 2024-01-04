<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();

        // Prevent caching by setting Cache-Control header
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/welcome');
    }
}
