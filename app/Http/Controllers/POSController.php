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

    public function addCustomer(Request $request)
    {

        $customers = new Customer;
        $customers->fname = $request->input('fname');
        $customers->lname = $request->input('lname');
        $customers->mname = $request->input('mname');
        $customers->suffix = $request->input('suffix');
        $customers->sex = $request->input('sex');
        $customers->phone = $request->input('phone');
        $customers->birthday = $request->input('birthday');
        $customers->unit = $request->input('unit');
        $customers->street = $request->input('street');
        $customers->village = $request->input('village');
        $customers->province = $request->input('province');
        $customers->city = $request->input('city');
        $customers->zipcode = $request->input('zipcode');
        $customers->save();

        return response()->json(['message' => 'Customer added successfully']);
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
    $transaction->vatable = $request->input('vatable');
    $transaction->vat = $request->input('vat');
    $transaction->paid_amount = $request->input('paid_amount');
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

}
