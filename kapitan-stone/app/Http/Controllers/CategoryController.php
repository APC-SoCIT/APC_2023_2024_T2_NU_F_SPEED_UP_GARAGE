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
    
    public function checkCategory(Request $request)
    {
        $name = $request->input('name');

        // Check if the category name already exists (case-insensitive)
        $category = Category::where('name', 'ILIKE', $name)->first();

        return response()->json(['exists' => $category ? true : false]);
    }
}
