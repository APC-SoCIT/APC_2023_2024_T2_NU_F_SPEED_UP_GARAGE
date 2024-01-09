<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(); // Fetch all products from the database
        return view('inventory', ['products' => $products]); // Pass products to the view
    }

    public function addProduct(Request $request) {
        // Validate request data (add your own validation logic here)
    
        // Insert the product into the database
        $product = new Product;
        $product->tag = $request->input('tag');
        $product->product_name = $request->input('product_name');
        $product->category = $request->input('category');
        $product->brand = $request->input('brand');
        $product->quantity = $request->input('quantity');
        $product->price = $request->input('price');
        $product->save();
    
        return response()->json(['message' => 'Product added successfully']);
    }
    
}



    // Implement other CRUD actions like create, store, show, edit, update, destroy.
