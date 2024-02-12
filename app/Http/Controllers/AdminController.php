<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Threshold; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Retrieve all products and transactions
        $products = Product::all();
        $transactions = Transaction::all();

        $user = Auth::user();
        $userRole = $user ? $user->role : null;

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

        // Retrieve the current threshold dynamically
        $threshold = Threshold::first();
        $lowStockThreshold = $threshold ? $threshold->value : 0;

        // Retrieve low-stock items (quantity is less than the threshold)
        $lowStockItems = $products->filter(function ($product) use ($lowStockThreshold) {
            return $product->quantity > 0 && $product->quantity < $lowStockThreshold;
        });

        // Calculate today's sales
        $todaySales = $transactions->where('created_at', '>=', Carbon::today())->sum('total_amount');
        $formattedTodaySales = number_format($todaySales, 2, '.', ',');

        // Calculate the number of products sold today
        $productsSoldToday = $transactions->where('created_at', '>=', Carbon::today())->sum('quantity');

        $currentMonthSales = Transaction::whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)
        ->sum('total_amount');

        $formattedCurrentMonthSales = number_format($currentMonthSales, 2, '.', ',');

        // Calculate average daily sales for the current month
        $daysInCurrentMonth = Carbon::now()->daysInMonth;
        $averageDailySales = $currentMonthSales / $daysInCurrentMonth;

        $formattedAverageDailySales = number_format($averageDailySales, 2, '.', ',');

        $recentTransactions = Transaction::latest()->take(3)->get();

        $dailySalesData = Transaction::select(DB::raw('SUM(total_amount) as total_sales'), DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'))
        ->where('created_at', '>=', Carbon::now()->subDays(9))
        ->groupBy('day')
        ->orderBy('day')
        ->pluck('total_sales', 'day')
        ->toArray();

        $lastSixMonthsSalesData = Transaction::select(
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_year')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->pluck('total_sales', 'month_year')
            ->toArray();
        
        // Create an array with sales data for each of the last six months
        $lastSixMonths = collect(range(5, 0, -1))->map(function ($i) use ($lastSixMonthsSalesData) {
            $monthYear = Carbon::now()->subMonths($i)->format('Y-m');
            return $lastSixMonthsSalesData[$monthYear] ?? 0;
        });

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
            'dailySalesData' => $dailySalesData,
            'lastSixMonthsSalesData' => $lastSixMonths->toArray(),
            'userRole' => $userRole,
        ]);
    }
}
