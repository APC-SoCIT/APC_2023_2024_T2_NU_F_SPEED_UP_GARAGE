<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

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
        $users = new User;
        $users->name = $request->input('name');
        $users->role = $request->input('role');
        $users->email = $request->input('email');
        $users->password = $request->input('password');
        $users->save();

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

        $users = User::find($id);
    
        if ($users) {
            $users->delete();
            return response()->json(['message' => 'User deleted successfully']);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }


}
