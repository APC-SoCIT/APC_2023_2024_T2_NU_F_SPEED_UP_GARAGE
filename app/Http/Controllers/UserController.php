<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function showUsers()
    {
        $users = User::all();
        return view('users', ['users' => $users]);
    }

    public function addUser(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'role' => 'required|in:1,2,3', // Assuming role can only be 1, 2, or 3
            'username' => 'required|unique:users',
            'password' => 'required|string|min:8',
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            'birthdate' => 'nullable|date',
            'contact_number' => 'required|string',
            'address' => 'required|string',
        ]);

        // Insert the user into the database
        $user = new User;
        $user->role = $validatedData['role'];
        $user->username = $validatedData['username'];
        $user->password = bcrypt($validatedData['password']); // Hash the password
        $user->save();

        // Create associated employee
        $employee = new Employee;
        $employee->user_id = $user->id; // Set the user_id in the employee record

        // Set employee fields
        $employee->fname = $validatedData['fname'];
        $employee->mname = $validatedData['mname'];
        $employee->lname = $validatedData['lname'];
        $employee->birthdate = $validatedData['birthdate'];
        $employee->contact_number = $validatedData['contact_number'];
        $employee->address = $validatedData['address'];

        $employee->save();

        return response()->json(['message' => 'User added successfully']);
    }

    public function editUser($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function updateUser(Request $request, $id)
    {
    // Validate request data
    $validatedData = $request->validate([
        'fname' => 'required|string',
        'mname' => 'nullable|string',
        'lname' => 'required|string',
        'birthdate' => 'nullable|date',
        'contact_number' => 'required|string',
        'address' => 'required|string',
        'username' => 'required',
        'password' => 'nullable|string|min:8', // Adjust validation rules
        'role' => 'required|in:1,2,3', // Assuming role can only be 1, 2, or 3
    ]);

    // Find the user by ID
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Update user data
    $user->username = $validatedData['username'];
    $user->role = $validatedData['role'];

    // Update password only if a new password is provided
    if (!empty($validatedData['password'])) {
        $user->password = bcrypt($validatedData['password']);
    }

    $user->save();

    // Update associated employee data
    $employee = Employee::where('user_id', $user->id)->first();
    $employee->fname = $validatedData['fname'];
    $employee->mname = $validatedData['mname'];
    $employee->lname = $validatedData['lname'];
    $employee->birthdate = $validatedData['birthdate'];
    $employee->contact_number = $validatedData['contact_number'];
    $employee->address = $validatedData['address'];
    $employee->save();

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

}
