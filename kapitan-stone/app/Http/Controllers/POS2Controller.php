<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class POS2Controller extends Controller
{
    public function showPOS2()
{
    // Retrieve all products from the Product model
    $products = Product::all();

    // Check if the request wants JSON
    if (request()->expectsJson()) {
        return response()->json($products);
    }

    // Pass the products to the 'POS' viewa
    return view('POS2', ['products' => $products]);
}
}