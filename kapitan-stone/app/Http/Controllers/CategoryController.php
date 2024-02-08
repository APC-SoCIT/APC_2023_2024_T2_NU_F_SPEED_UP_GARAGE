<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Insert the category into the database
        Category::create([
            'name' => $request->input('name'),
        ]);

        return response()->json(['message' => 'Category added successfully']);
    }
}
