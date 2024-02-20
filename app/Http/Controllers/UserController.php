<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
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
        // Validate request data (add your own validation logic here)
    
        // Insert the user into the database
        $user = new User;
        $user->role = $request->input('role');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->save();
    
        // Create associated employee
        $employee = new Employee;
        $employee->user_id = $user->id; // Set the user_id in the employee record
        
        // Set employee fields
        $employee->fname = $request->input('fname');
        $employee->mname = $request->input('mname');
        $employee->lname = $request->input('lname');
        $employee->birthdate = $request->input('birthdate');
        $employee->contact_number = $request->input('contact_number');
        $employee->address = $request->input('address');
        
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
            'birthdate' => 'required|date',
            'contact_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string',
            'password' => 'nullable|string|min:8', // Adjust validation rules
            'role' => 'required|in:1,2,3', // Assuming role can only be 1, 2, or 3
        ]);
    
        // Find the user by ID
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // Update user data
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']); // Always update the password if provided
        $user->role = $validatedData['role'];
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
