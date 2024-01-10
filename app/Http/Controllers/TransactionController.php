<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::all();
        return view('transactions', ['transactions' => $transactions]);
    }

    public function addTransaction(Request $request)
    {
        // Validate request data (add your own validation logic here)

        // Insert the transaction into the database
        $transaction = new Transaction;
        $transaction->customer_name = $request->input('customer_name');
        $transaction->phone = $request->input('phone');
        $transaction->date = $request->input('date');
        $transaction->item = $request->input('item');
        $transaction->quantity = $request->input('quantity');
        $transaction->total_amount = $request->input('total_amount');
        $transaction->payment_method = $request->input('payment_method');
        $transaction->status = $request->input('status');
        $transaction->cashier_name = $request->input('cashier_name');
        $transaction->save();

        return response()->json(['message' => 'Transaction added successfully']);
    }

    public function editTransaction($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction) {
            return response()->json(['transaction' => $transaction]);
        } else {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
    }

    public function updateTransaction(Request $request, $id)
{
    // Validate request data
    $validatedData = $request->validate([
        'customer_name' => 'required|string',  
        'phone' => 'required|string',  
        'date' => 'required|date',  
        'item' => 'required|string',  
        'quantity' => 'required|integer',  
        'total_amount' => 'required|integer',  
        'payment_method' => 'required|string',  
        'status' => 'required|string',  
        'cashier_name' => 'required|string',  
    ]);
    
    // Find the transaction by ID
    $transaction = Transaction::find($id);

    if (!$transaction) {
        return response()->json(['error' => 'Transaction not found'], 404);
    }

    // Update the transaction fields
    $transaction->customer_name = $validatedData['customer_name'];
    $transaction->phone = $validatedData['phone'];
    $transaction->date = $validatedData['date'];
    $transaction->item = $validatedData['item'];
    $transaction->quantity = $validatedData['quantity'];
    $transaction->total_amount = $validatedData['total_amount'];
    $transaction->payment_method = $validatedData['payment_method'];
    $transaction->status = $validatedData['status'];
    $transaction->cashier_name = $validatedData['cashier_name'];
    $transaction->save();

    return response()->json(['message' => 'Transaction Updated successfully']);
}

    public function deleteTransaction($id)
    {
        // Find the transaction by ID and delete it
        $transaction = Transaction::find($id);
    
        if ($transaction) {
            $transaction->delete();
            return response()->json(['message' => 'Transaction deleted successfully']);
        } else {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
    }
}
