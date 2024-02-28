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
    
        return view('inventory', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
            'users' => $users
        ]);
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
