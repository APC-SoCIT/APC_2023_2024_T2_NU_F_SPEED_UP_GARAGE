<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\InventoryLog;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $users = User::all();
        $brands = Brand::all();
        $categories = Category::all();
    
        return view('products', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
            'users' => $users
        ]);
    }

    public function getProductByBarcode(Request $request)
{
    $tag = $request->input('barcode'); // Retrieve the scanned tag (barcode)
    $product = Product::where('tag', $tag)->first(); // Query the Product table using the tag

    if ($product) {
        return response()->json([
            'id' => $product->id,
            'tag' => $product->tag,
            'product_name' => $product->product_name,
            'quantity' => $product->quantity,
            'price' => $product->price,
            'category' => $product->category,
            'brand' => $product->brand,
            'product_image' => asset('storage/product_images/' . $product->product_image),
            // Include other attributes as needed
        ]);
    } else {
        return response()->json(['error' => 'Product not found for the given barcode'], 404);
    }
}


    public function getProducts()
    {
        $products = Product::all();
        return response()->json(['products' => $products]);
    }

    public function invreport()
    {
        $inventory_logs = InventoryLog::all();
        return view('inventory-reports', compact('inventory_logs'));
    }
}

