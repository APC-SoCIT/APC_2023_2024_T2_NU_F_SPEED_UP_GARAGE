<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\User;

class POSController extends Controller
{

    public function showPOS()
    {

        $transactions = Transaction::all();
        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();

        // Check if the request wants JSON
        if (request()->expectsJson()) {
            // Convert encoding before returning JSON
            $utf8EncodedProducts = $this->convertArrayEncoding($products);
            return response()->json($utf8EncodedProducts);
        }

        // Pass the products to the 'POS' view
        return view('POS', compact('products', 'customers', 'transactions', 'users'));
    }

    private function convertArrayEncoding($array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            if (is_string($item)) {
                // Replace or ignore invalid UTF-8 characters
                $item = iconv('UTF-8', 'UTF-8//IGNORE', $item);
            }
        });

        return $array;
    }

    public function index()
    {
        $customers = Customer::all();

        return view('POS', ['customers' => $customers]);
    }

    public function getLatestTransactionId()
{
    $latestTransaction = Transaction::latest('transaction_id')->first();

    return response()->json(['latestTransactionId' => optional($latestTransaction)->id]);
}

public function addTransaction(Request $request)
{
    $transaction = new Transaction;
    $transaction->customer_name = $request->input('customer_name');
    $transaction->phone = $request->input('phone');
    $transaction->date = $request->input('date');
    $transaction->payment_total = $request->input('payment_total');
    $transaction->customer_change = $request->input('customer_change');
    $transaction->quantity = $request->input('quantity');
    $transaction->total_amount = $request->input('total_amount');
    $transaction->items = $request->input('items');
    $transaction->qty = $request -> input('qty');
    $transaction->payment_method = $request->input('payment_method');
    $transaction->status = $request->input('status');
    $transaction->cashier_name = $request->input('cashier_name');
    
    $transaction->save();
    
    $items = $request->input('items');
    $qtys = $request->input('qty');

    if ($items) {
        foreach ($items as $index => $itemName) {
            $product = Product::where('product_name', $itemName)->first();

            if ($product) {
                // Deduct the quantity based on the purchased quantity
                $product->quantity -= $qtys[$index];
                $product->save();
            }
        }
    }

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

    if (!$transaction) {
        return response()->json(['error' => 'Transaction not found'], 404);
    }

// Update the transaction fields
$transaction->customer_name = $validatedData['customer_name'];
    $transaction->phone = $validatedData['phone'];
    $transaction->date = $validatedData['date'];
    $transaction->items = $validatedData['items'];
    $transaction->qty = $validatedData['qty'];
    $transaction->quantity = $validatedData['quantity'];  // Keep only one line for updating quantity
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
