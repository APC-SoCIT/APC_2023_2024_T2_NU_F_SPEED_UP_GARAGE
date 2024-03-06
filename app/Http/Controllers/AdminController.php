<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Threshold;
use App\Models\TopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function getTopProducts()
    {
        $topProducts = TopProduct::all();
        return response()->json($topProducts);
    }

    public function updateTopProduct(Request $request, $id)
    {
        $topProduct = TopProduct::findOrFail($id);
        
        // Check if the created_at month is the current month
        if (now()->isSameMonth($topProduct->created_at)) {
            // If the created_at month is the current month, save the product and return success message
            $topProduct->save();
            return response()->json(['message' => 'Quantity sold data updated successfully']);
        } else {
            // If the created_at month is not the current month, delete the product and return success message
            $topProduct->delete();
            return response()->json(['message' => 'Data deleted successfully as created_at month is not the current month.']);
        }
    }

    public function index()
    {
        $topProductsData = TopProduct::all();
        $products = Product::all();
        $transactions = Transaction::all();
        $transactionsToday = Transaction::whereDate('created_at', Carbon::today())->get();
        $totalLaborSalesToday = $transactionsToday->sum('labor_amount');

        $transactionsToday = Transaction::whereDate('created_at', Carbon::today())->get();
        $totalSalesToday = $transactionsToday->sum('total_amount');

        $totalProductSalesToday = $totalSalesToday - $totalLaborSalesToday;

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
            return $product->quantity > 0 && $product->quantity <= $lowStockThreshold;
        });

        // Calculate today's sales
        $todaySales = $transactions->where('created_at', '>=', Carbon::today())->sum('total_amount');
        $formattedTodaySales = number_format($todaySales, 2, '.', ',');

        $productsSoldToday = $transactions->where('created_at', '>=', Carbon::today())->sum('quantity');

        $currentMonthSales = Transaction::whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)
        ->sum('total_amount');

        $formattedCurrentMonthSales = number_format($currentMonthSales, 2, '.', ',');

        // Calculate average daily sales for the current month
        $currentDate = Carbon::now();
        $firstDayOfMonth = $currentDate->copy()->startOfMonth();
        $currentDayOfMonth = $currentDate->day;
        
        // Calculate the sum of sales from the first day of the month up to the current day
        $currentMonthSalesToDate = Transaction::where('created_at', '>=', $firstDayOfMonth)
            ->where('created_at', '<=', $currentDate)
            ->sum('total_amount');
        
        // Calculate the average daily sales
        $averageDailySales = $currentMonthSalesToDate / $currentDayOfMonth;
        
        $formattedAverageDailySales = number_format($averageDailySales, 2, '.', ',');

        $recentTransactions = Transaction::latest()->take(10)->get();

        $currentDate = Carbon::now();
        $currentMonth = Carbon::now()->monthName;

        // Get the count of transactions that occurred today
        $transactionsTodayCount = Transaction::whereDate('created_at', $currentDate)->count();

        $dailySalesData = Transaction::select(DB::raw('SUM(total_amount) as total_sales'), DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'))
        ->where('created_at', '>=', Carbon::now()->subDays(9))
        ->groupBy('day')
        ->orderBy('day')
        ->pluck('total_sales', 'day')
        ->toArray();

        

        $yesterdaySales = $dailySalesData[date('Y-m-d', strtotime('-1 day'))] ?? 0;
        $todaySales = end($dailySalesData) ?? 0;

        // Calculate the difference in sales
        $difference = $todaySales - $yesterdaySales;

        // Calculate the percentage change
        $percentageChange = ($difference / ($yesterdaySales == 0 ? 1 : $yesterdaySales)) * 100; // Prevent division by zero

        $percentageChangeSign = $percentageChange >= 0 ? '+' : '-';
        $percentageChangeColor = $percentageChange >= 0 ? 'green' : 'red';

        // Display the percentage change



        $currentMonth = Carbon::now()->format('Y-m');
        $previousMonth = Carbon::now()->subMonth()->format('Y-m');
        
        // Retrieve sales data for the last six months
        $lastSixMonthsSalesData = Transaction::select(
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_year')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->pluck('total_sales', 'month_year')
            ->toArray();
        
        // Calculate current month and previous month sales
        $currentMonthSales = $lastSixMonthsSalesData[$currentMonth] ?? 0;
        $previousMonthSales = $lastSixMonthsSalesData[$previousMonth] ?? 0;
        
        // Calculate gain
        $gain = $currentMonthSales - $previousMonthSales;
        
        // Determine gain sign and color
        $gainSign = $gain >= 0 ? '+' : '-';
        $gainColor = $gain >= 0 ? 'green' : 'red';
        
        // Format gain as currency
        $formattedGain = '₱' . number_format(abs($gain), 2, '.', ',');
        
        // Create an array for percentMonth
        $percentMonth = [
            'value' => $formattedGain,
            'sign' => $gainSign,
            'color' => $gainColor,
        ];
        
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
            'topProductsData' => $topProductsData,
            'transactionsTodayCount' => $transactionsTodayCount,
            'currentMonth' => $currentMonth,
            'totalLaborSalesToday' => '₱' . number_format($totalLaborSalesToday, 2, '.', ','),
            'totalProductSalesToday' =>'₱' . number_format($totalProductSalesToday, 2, '.', ','),
            'percentageChange' => '%' . number_format($percentageChange, 2, '.', ','),
            'percentageChange' => [
                'value' => $percentageChange,
                'sign' => $percentageChangeSign,
                'color' => $percentageChangeColor,
                
            ],
            'percentMonth' => $percentMonth,
        ]);
    }
} 

