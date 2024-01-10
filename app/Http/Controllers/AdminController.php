<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Retrieve all products
        $products = Product::all();

        $totalItemsOnHand = $products->sum('quantity');

        // Calculate the total inventory value by summing the product of quantity and price for each product
        $totalInventoryValue = $products->sum(function ($product) {
            return $product->quantity * $product->price;
        });

        $outOfStockItems = $products->filter(function ($product) {
            return $product->quantity == 0;
        });

        $lowStockThreshold = 25;

        // Retrieve low-stock items (quantity is less than the threshold)
        $lowStockItems = $products->filter(function ($product) use ($lowStockThreshold) {
            return $product->quantity > 0 && $product->quantity < $lowStockThreshold;
        });

        // Pass the products and total inventory value to the view
        return view('admin', [
            'products' => $products,
            'totalInventoryValue' => $totalInventoryValue,
            'totalItemsOnHand' => $totalItemsOnHand,
            'outOfStockItems' => $outOfStockItems,
            'lowStockItems' => $lowStockItems,
        ]);
    }
}

