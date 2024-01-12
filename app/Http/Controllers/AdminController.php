<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Retrieve all products and transactions
        $products = Product::all();
        $transactions = Transaction::all();

        $totalItemsOnHand = $products->sum('quantity');
        $formattedItemsOnHand = number_format($totalItemsOnHand, 0, '.', ',');

        // Calculate the total inventory value by summing the product of quantity and price for each product
        $totalInventoryValue = $products->sum(function ($product) {
            return $product->quantity * $product->price;
        });

        $formattedTotalInventoryValue = number_format($totalInventoryValue, 2, '.', ',');

        $outOfStockItems = $products->filter(function ($product) {
            return $product->quantity == 0;
        });

        $lowStockThreshold = 25;

        // Retrieve low-stock items (quantity is less than the threshold)
        $lowStockItems = $products->filter(function ($product) use ($lowStockThreshold) {
            return $product->quantity > 0 && $product->quantity < $lowStockThreshold;
        });

        // Calculate today's sales
        $todaySales = $transactions->where('created_at', '>=', Carbon::today())->sum('total_amount');
        $formattedTodaySales = number_format($todaySales, 2, '.', ',');

        // Calculate the number of products sold today
        $productsSoldToday = $transactions->where('created_at', '>=', Carbon::today())->sum('quantity');

        // Calculate monthly sales
        $currentMonthSales = $transactions->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('total_amount');
        $formattedCurrentMonthSales = number_format($currentMonthSales, 2, '.', ',');

        // Calculate the average daily sales for the month
        $daysInMonth = Carbon::now()->daysInMonth;
        $averageDailySales = $currentMonthSales / $daysInMonth;
        $formattedAverageDailySales = number_format($averageDailySales, 2, '.', ',');

        $recentTransactions = Transaction::latest()->take(3)->get();

        $nineDaySalesData = [];
        $dates = Carbon::now()->subDays(9)->format('m/d/Y');

        for ($i = 0; $i < 9; $i++) {
            $nineDaySalesData[$dates] = isset($nineDaySales[$dates]) ? $nineDaySales[$dates] : 0;
            $dates = Carbon::parse($dates)->addDay()->format('m/d/Y');
        }

        return view('admin', [
            'products' => $products,
            'formattedTotalInventoryValue' => $formattedTotalInventoryValue,
            'formattedItemsOnHand' => $formattedItemsOnHand,
            'outOfStockItems' => $outOfStockItems,
            'lowStockItems' => $lowStockItems,
            'formattedTodaySales' => $formattedTodaySales,
            'productsSoldToday' => $productsSoldToday,
            'formattedCurrentMonthSales' => $formattedCurrentMonthSales,
            'formattedAverageDailySales' => $formattedAverageDailySales,
            'recentTransactions' => $recentTransactions,
            'nineDaySalesData' => $nineDaySalesData,
        ]);
    }

    
    
}
