<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('inventory', ['products' => $products]);
    }

    public function addProduct(Request $request)
    {
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

    public function editProduct($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    // ProductController.php

    public function updateProduct(Request $request, $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'tag' => 'required|string',  // Add validation for tag
            'product_name' => 'required|string',  // Add validation for product_name
            'category' => 'required|string',  // Add validation for category
            'brand' => 'required|string',  // Add validation for brand
            // Add more validation rules as needed
        ]);
    
        // Find the product by ID
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        // Update product data
        $product->quantity = $validatedData['quantity'];
        $product->price = $validatedData['price'];
        $product->tag = $validatedData['tag'];  // Update tag
        $product->product_name = $validatedData['product_name'];  // Update product_name
        $product->category = $validatedData['category'];  // Update category
        $product->brand = $validatedData['brand'];  // Update brand
        $product->save();
    
        return response()->json(['message' => 'Product updated successfully']);
    }
    


    public function deleteProduct($id)
    {
        // Find the product by ID and delete it
        $product = Product::find($id);
    
        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
    
}