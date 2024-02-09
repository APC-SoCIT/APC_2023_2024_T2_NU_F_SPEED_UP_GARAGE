<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller {

    public function addBrand(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Insert the brand into the database
        Brand::create([
            'name' => $request->input('name'),
        ]);

        return response()->json(['message' => 'Brand added successfully']);
    }

    public function checkBrand(Request $request)
    {
        $name = $request->input('name');

        // Check if the brand name already exists (case-insensitive)
        $brand = Brand::where('name', 'ILIKE', $name)->first();

        return response()->json(['exists' => $brand ? true : false]);
    }
}
