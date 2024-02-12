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

        // Check if the category already exists
        $existingCategory = Category::where('name', $request->input('name'))->first();

        // If the category already exists, return an error message
        if ($existingCategory) {
            return response()->json(['error' => 'Category already exists'], 409); // 409 Conflict
        }

        // Insert the category into the database
        Category::create([
            'name' => $request->input('name'),
        ]);

        return response()->json(['message' => 'Category added successfully']);
    }
}
