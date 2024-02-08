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
    

        $transactions = Transaction::all();

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
        $todayTransactions = [];
    
        // Calculate aggregated data for each date
        foreach ($groupedTransactions as $date => $group) {
            $dates[] = $date;
            $todaySales[] = number_format($group->sum('total_amount'), 2, '.', ',');
            $todayTransactions[] = $group->count();
        }
    
        return view('sales-reports', [
            'dates' => $dates,
            'todaySales' => $todaySales,
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
    $transaction->payment_method = $request->input('payment_method');
    $transaction->status = $request->input('status');
    $transaction->cashier_name = $request->input('cashier_name');
    $transaction->save();



    return response()->json(['message' => 'Transaction added successfully']);
}

    public function editTransaction($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);

        if ($transaction) {
            return response()->json(['transaction' => $transaction]);
        } else {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
    }

    public function updateTransaction(Request $request, $transaction_id)
{
    // Validate request data
    $validatedData = $request->validate([
        'customer_name' => 'required|string',  
        'phone' => 'required|string',  
        'date' => 'required|date',  
        'items' => 'required|string',  
        'qty' => 'required|string',
        'quantity' => 'required|integer',  
        'total_amount' => 'required|integer',
        'payment_total' => 'required|integer',
        'customer_change' => 'required|integer',
        'payment_method' => 'required|string',  
        'status' => 'required|string',  
        'cashier_name' => 'required|string',  
    ]);
    
    // Find the transaction by ID
    $transaction = Transaction::find($transaction_id);
    return response()->json($validatedData);

    if (!$transaction) {
        return response()->json(['error' => 'Transaction not found'], 404);
    }

    // Update the transaction fields
    $transaction->customer_name = $validatedData['customer_name'];
    $transaction->phone = $validatedData['phone'];
    $transaction->date = $validatedData['date'];
    $transaction->items = $validatedData['items'];
    $transaction->qty = $validatedData['qty'];
    $transaction->quantity = $request->input('quantity');
    $transaction->total_amount = $validatedData['total_amount'];
    $transaction->payment_total = $validatedData['payment_total'];
    $transaction->customer_change = $validatedData['customer_change'];
    $transaction->payment_method = $validatedData['payment_method'];
    $transaction->status = $validatedData['status'];
    $transaction->cashier_name = $validatedData['cashier_name'];
    $transaction->save();

    return response()->json(['message' => 'Transaction Updated successfully']);
}

    public function deleteTransaction($transaction_id)
    {
        // Find the transaction by ID and delete it
        $transaction = Transaction::find($transaction_id);
    
        if ($transaction) {
            $transaction->delete();
            return response()->json(['message' => 'Transaction deleted successfully']);
        } else {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
    }
}
