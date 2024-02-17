<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has associated employee data
        $employee = $user->employee;

        // Pass the user and employee data to the settings view
        return view('settings', compact('user', 'employee'));
    }
    // In your controller
    public function showSettings()
    {
        $user = Auth::user(); // Assuming you're using Laravel's authentication
        return view('Settings', compact('user'));
    }
    
}
