<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

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

    public function getAvatar($filename)
    {
        $path = storage_path('app/public/avatars/' . $filename);
    
        if (!file_exists($path)) {
            abort(404);
        }
    
        $file = file_get_contents($path);
        $type = mime_content_type($path);
    
        return (new Response($file, 200))->header('Content-Type', $type);
    }
    
    public function updateProfile(Request $request, $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'firstName' => 'required|string',
            'middleName' => 'required|string',
            'lastName' => 'required|string',
            'birthDate' => 'required|date',
            'contactNumber' => 'required|string',
            'address' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate avatar upload
        ]);
    
        // Find the user by ID
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
    
        // Update associated employee data if available
        if ($user->employee) {
            $employee = $user->employee;
            $employee->fname = $validatedData['firstName'];
            $employee->mname = $validatedData['middleName'];
            $employee->lname = $validatedData['lastName'];
            $employee->birthdate = $validatedData['birthDate'];
            $employee->contact_number = $validatedData['contactNumber'];
            $employee->address = $validatedData['address'];
    
            // Check if avatar is uploaded
            if ($request->hasFile('avatar')) {
                // Delete the existing avatar if it exists
                if ($employee->profile_picture) {
                    Storage::disk('public')->delete($employee->profile_picture);
                }
    
                // Store the uploaded avatar file
                $avatarPath = $request->file('avatar')->store('avatars', 'public'); // Specify the public disk
    
                // Update the profile_picture column in the employee table
                $employee->profile_picture = $avatarPath;
            } elseif ($request->has('delete_avatar')) {
                // Delete the existing avatar if it exists
                if ($employee->profile_picture) {
                    Storage::disk('public')->delete($employee->profile_picture);
                }
                // Use the default image path for the update
                $employee->profile_picture = 'avatars/default-image.png';
            }
    
            $employee->save();
        } else {
            // Log message if employee data is not available
            Log::info('Employee data not available');
        }
    
        return response()->json(['success' => 'Profile updated successfully']);
    }
}
