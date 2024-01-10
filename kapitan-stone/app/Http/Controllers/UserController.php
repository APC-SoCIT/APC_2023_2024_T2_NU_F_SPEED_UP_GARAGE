<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUsers()
    {
        $users = User::all(); // Retrieve users from the database
        return view('Users', ['users' => $users]);
    }
}