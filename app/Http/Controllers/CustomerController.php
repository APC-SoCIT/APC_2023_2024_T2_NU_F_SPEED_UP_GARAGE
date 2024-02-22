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
        $customers->barangay = $request->input('barangay');
        $customers->zipcode = $request->input('zipcode');
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
            'fname' => 'required|string',  
            'lname' => 'required|string',  
            'mname' => 'required|string',  
            'suffix' => 'nullable|string',  
            'sex' => 'nullable|string',  
            'phone' => 'nullable|string',  
            'birthday' => 'nullable|string',  // Add validation for birthday field
            'unit' => 'nullable|string',       // Add validation for unit field
            'street' => 'nullable|string',     // Add validation for street field
            'village' => 'nullable|string',    // Add validation for village field
            'province' => 'nullable|string',   // Add validation for province field
            'city' => 'nullable|string',       // Add validation for city field
            'barangay' => 'nullable|string',
            'zipcode' => 'nullable|string',  
        ]);
    
        // Find the customer by ID
        $customer = Customer::find($id);
    
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    
        // Update customer data
        $customer->fname = $validatedData['fname'];
        $customer->lname = $validatedData['lname'];
        $customer->mname = $validatedData['mname'];
        $customer->suffix = $validatedData['suffix'];
        $customer->sex = $validatedData['sex'];
        $customer->phone = $validatedData['phone'];
        $customer->birthday = $validatedData['birthday'];
        $customer->unit = $validatedData['unit'];
        $customer->street = $validatedData['street'];
        $customer->village = $validatedData['village'];
        $customer->province = $validatedData['province'];
        $customer->city = $validatedData['city'];
        $customer->barangay = $validatedData['barangay']; // Corrected this line
        $customer->zipcode = $validatedData['zipcode'];
        $customer->save();
    
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