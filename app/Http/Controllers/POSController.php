<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\User;
use App\Models\Brand;
use App\Models\TopProduct;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;


class POSController extends Controller
{

    public function showPOS()
    {

        $transactions = Transaction::all();
        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();
        $brands = Brand::all();
        $categories = Category::all();

        // Check if the request wants JSON
        if (request()->expectsJson()) {
            // Convert encoding before returning JSON
            $utf8EncodedProducts = $this->convertArrayEncoding($products);
            return response()->json($utf8EncodedProducts);
        }

        // Pass the products to the 'POS' view
        return view('POS', compact('products', 'customers', 'transactions', 'users', 'brands', 'categories',));
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

        $userId = Auth::id();
    
        // Create a new transaction instance
        $transaction = new Transaction;
        
        // Assign the user's ID to the transaction
        $transaction->user_id = $userId;

        // Create a new transaction instance
        $transaction = new Transaction;
        $transaction->user_id = $request->input('user_id'); // Add user_id
        $transaction->customer_name = $request->input('customer_name');
        $transaction->phone = $request->input('phone');
        $transaction->date = $request->input('date');
        $transaction->vatable = $request->input('vatable');
        $transaction->vat = $request->input('vat');
        $transaction->cash_amount = $request->input('cash_amount');
        $transaction->gcash_amount = $request->input('gcash_amount');
        $transaction->card_amount = $request->input('card_amount');
        $transaction->total_payment = $request->input('total_payment');
        $transaction->customer_change = $request->input('customer_change');
        $transaction->quantity = $request->input('quantity');
        $transaction->total_amount = $request->input('total_amount');
        $transaction->items = $request->input('items');
        $transaction->qty = $request->input('qty');
        $transaction->payment_method = $request->input('payment_method');
        $transaction->status = $request->input('status');
        $transaction->cashier_name = $request->input('cashier_name');
        
        $laborAmount = $request->input('labor_amount');
        $transaction->labor_amount = $laborAmount;
        $transaction->save();
        
        // Retrieve items and quantities from the request
        $items = explode(', ', $request->input('items'));
        $qtys = explode(', ', $request->input('qty'));

        // Log the received items and quantities
        Log::info('Items and qtys:', ['items' => $items, 'qtys' => $qtys]);

        // Ensure items and quantities are properly defined and match in length
        if (count($items) === count($qtys)) {
            foreach ($items as $index => $itemName) {
                // Check if the item is named "Labor"
                if ($itemName === "Labor") {
                    continue; // Skip processing for "Labor" item
                }

                $product = Product::where('product_name', $itemName)->first();
            
                if ($product) {
                    
                    $product->save();
                    $topProduct = TopProduct::where('item_name', $itemName)->first();
                    if ($topProduct) {

                        $topProduct->quantity_sold += $qtys[$index];
                    } else {
                        $topProduct = new TopProduct();
                        $topProduct->item_name = $itemName;
                        $topProduct->quantity_sold = $qtys[$index];
                    }
                    $topProduct->save();
                } else {
                    Log::error('Product not found for item name: ' . $itemName);
                    return response()->json(['error' => 'Product not found for item name: ' . $itemName], 400);
                }
            }
        }
    }
}