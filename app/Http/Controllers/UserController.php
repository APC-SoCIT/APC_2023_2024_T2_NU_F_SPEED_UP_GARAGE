<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function showUsers()
    {
        $users = User::all();
        return view('users', ['users' => $users]);
    }

    public function addUser(Request $request)
    {
        // Validate request data (add your own validation logic here)

        // Insert the user into the database
        $user = new User;
        $user->name = $request->input('name');
        $user->role = $request->input('role');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->save();

        // Create associated employee
        $employee = new Employee;
        $employee->user_id = $user->id; // Set the user_id in the employee record
        // Add other fields as needed
        $employee->save();

        return response()->json(['message' => 'User added successfully']);
    }

    public function editUser($id)
    {
        $users = User::find($id);

        if ($users) {
            return response()->json(['user' => $users]);
        } else {
            return response()->json(['error' => 'user not found'], 404);
        }
    }

    public function updateUser(Request $request, $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'nullable|string|min:8',  // Adjust validation rules
        ]);
    
        // Find the user by ID
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // Update user data
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
    
        // Check if the password is provided and update it
        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);  // Hash the password
        }
    
        $user->save();
    
        return response()->json(['message' => 'User updated successfully']);
    }
    
    public function deleteUser($id)
    {
        // Find the user with the given ID along with their associated employee record if it exists
        $user = User::with('employee')->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // If the user has an associated employee record, delete it first
        if ($user->employee) {
            $user->employee->delete();
        }

        // Now delete the user
        $user->delete();

        return response()->json(['message' => 'User and associated employee deleted successfully']);
    }
    
    public function updateProfile(Request $request, $id)
{
    // Validate request data
    $validatedData = $request->validate([
        'firstName' => 'required|string',
        'lastName' => 'required|string',
        'email' => 'required|email',
        'contactNumber' => 'nullable|string',
        'address' => 'nullable|string',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate avatar upload
    ]);

    // Find the user by ID
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Update user data
    $user->email = $validatedData['email'];
    $user->save();

    // Update associated employee data if available
    if ($user->employee) {
        $employee = $user->employee;
        $employee->fname = $validatedData['firstName'];
        $employee->lname = $validatedData['lastName'];
        $employee->contact_number = $validatedData['contactNumber'];
        $employee->address = $validatedData['address'];

        // Handle profile picture upload if provided
        if ($request->hasFile('avatar')) {
            // Get the uploaded file
            $avatarFile = $request->file('avatar');
            
            // Generate a unique filename
            $avatarFilename = time() . '_' . $avatarFile->getClientOriginalName();
            
            // Move the uploaded file to the public/avatars directory
            $avatarFile->move(storage_path('avatars'), $avatarFilename);
            
            // Update employee's profile picture path
            $employee->profile_picture = 'avatars/' . $avatarFilename;

            // Log success message
            Log::info('Avatar uploaded successfully: ' . $avatarFilename);
        } else {
            // Log message if no avatar was uploaded
            Log::info('No avatar uploaded');
        }

        $employee->save();
    } else {
        // Log message if employee data is not available
        Log::info('Employee data not available');
    }

    return redirect()->back()->with('success', 'Profile updated successfully');
}

}


