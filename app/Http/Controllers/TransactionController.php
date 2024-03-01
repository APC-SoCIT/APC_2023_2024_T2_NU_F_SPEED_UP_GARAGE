<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CartItem;
use Carbon\Carbon;

class TransactionController extends Controller
{

    public function index()
    {
        $products = Product::all(); // Assuming you have a Product model
    

        $transactions = Transaction::latest('transaction_id')->get();

        $customers = Customer::all();
    
        return view('transactions', [
            'products' => $products,
            'transactions' => $transactions,
            'customers' => $customers,
        ]);

        
    }

    public function salesrep()
    {
        $transactions = Transaction::all();
    
        // Group transactions by date
        $groupedTransactions = $transactions->groupBy(function ($transaction) {
            return Carbon::parse($transaction->created_at)->toDateString();
        });
    
        // Initialize arrays to store aggregated data
        $dates = [];
        $todaySales = [];
        $todayItemSales = [];
        $todayLaborSales = [];
        $todayTransactions = [];
    
        // Calculate aggregated data for each date
        foreach ($groupedTransactions as $date => $group) {
            $dates[] = $date;
            $todaySales[] = number_format($group->sum('total_amount'), 2, '.', ',');
            $todayItemSales[] = number_format($group->sum('total_amount') - $group->sum('labor_amount'), 2, '.', ',');
            $todayLaborSales[] = number_format($group->sum('labor_amount'), 2, '.', ',');            
            $todayTransactions[] = $group->count();
        }
    
        return view('sales-reports', [
            'dates' => $dates,
            'todaySales' => $todaySales,
            'todayItemSales' => $todayItemSales,
            'todayLaborSales' => $todayLaborSales,
            'todayTransactions' => $todayTransactions
        ]);
    }


    public function getLatestTransactionId()
{
    $latestTransaction = Transaction::latest('transaction_id')->first();

    return response()->json(['latestTransactionId' => optional($latestTransaction)->transaction_id]);
}


    public function getTransactions()
    {
        $transactions = Transaction::all();


        return response()->json(['transactions' => $transactions]);
    }

    public function addTransaction(Request $request)
    {
    
        $transaction = new Transaction;
    $transaction->customer_name = $request->input('customer_name');
    $transaction->phone = $request->input('phone');
    $transaction->date = $request->input('date');
    $transaction->payment_total = $request->input('payment_total');
    $transaction->customer_change = $request->input('customer_change');
    $transaction->items = $request->input('items');
    $transaction->qty = $request->input('qty');
    $transaction->quantity = $request->input('quantity');
    $transaction->total_amount = $request->input('total_amount');
    $transaction->total_payment = $request->input('total_payment');
    $transaction->payment_method = $request->input('payment_method');
    $transaction->status = $request->input('status');
    $transaction->cashier_name = $request->input('cashier_name');
    $transaction->save();



    return response()->json(['message' => 'Transaction added successfully']);
}

}
