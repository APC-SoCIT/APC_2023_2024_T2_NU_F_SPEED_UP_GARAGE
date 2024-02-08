<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $brands = Brand::all();
        $categories = Category::all();

        return view('products', compact('products', 'brands', 'categories'));
    }

    public function getProducts()
    {
        $products = Product::all();
        return response()->json(['products' => $products]);
    }

    public function invreport()
    {
        $products = Product::all();
        return view('inventory-reports', compact('products'));
    }
}
