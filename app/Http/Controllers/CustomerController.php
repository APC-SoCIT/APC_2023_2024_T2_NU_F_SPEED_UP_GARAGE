<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::all();
        return view('customers', ['customers' => $customers]);
    }

    public function addCustomer(Request $request)
    {
        // Validate request data (add your own validation logic here)

        // Insert the product into the database
        $customers = new Customer;
        $customers->customer_name = $request->input('customer_name');
        $customers->phone = $request->input('phone');
        $customers->address = $request->input('address');
        $customers->save();

        return response()->json(['message' => 'Customer added successfully']);
    }

    public function editCustomer($id)
    {
        $customers = Customer::find($id);

        if ($customers) {
            return response()->json(['customer' => $customers]);
        } else {
            return response()->json(['error' => 'customer not found'], 404);
        }
    }

    public function updateCustomer(Request $request, $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'customer_name' => 'required|string',  
            'phone' => 'required|string',  
            'address' => 'required|string',  
        ]);
    
        // Find the product by ID
        $customers = Customer::find($id);
    
        if (!$customers) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    
        // Update product data
        $customers->customer_name = $validatedData['customer_name'];
        $customers->phone = $validatedData['phone'];
        $customers->address = $validatedData['address'];  
        $customers->save();
    
        return response()->json(['message' => 'Customer updated successfully']);
    }

    public function deleteCustomer($id)
    {
        // Find the product by ID and delete it
        $customers = Customer::find($id);
    
        if ($customers) {
            $customers->delete();
            return response()->json(['message' => 'Customer deleted successfully']);
        } else {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    }


}
