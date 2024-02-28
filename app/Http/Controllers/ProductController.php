<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\UploadedFile; 
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $users = User::all();
        $brands = Brand::all();
        $categories = Category::all();
    
        return view('inventory', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
            'users' => $users
        ]);
    }

    public function addProduct(Request $request){
        // Validate request data
        $validatedData = $request->validate([
            'tag' => 'required|string',
            'product_name' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'brand' => 'required|string',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'updated_by' => 'required|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules for the image
        ]);
    
        // Insert the product into the database
        $product = new Product;
        $product->tag = $validatedData['tag'];
        $product->product_name = $validatedData['product_name'];
        $product->description = $validatedData['description'];
        $product->category = $validatedData['category'];
        $product->brand = $validatedData['brand'];
        $product->quantity = $validatedData['quantity'];
        $product->price = $validatedData['price'];
        $product->updated_by = $validatedData['updated_by'];
    
        // Handle file upload
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/product_images', $imageName);
            $product->product_image = $imageName;
        }
    
        $product->save();
    
        return response()->json(['message' => 'Product added successfully']);
    }

    public function updateQty(Request $request, $id){
        // Validate request data
        $validatedData = $request->validate([
            'quantity' => 'required|numeric',
        ]);
    
        // Retrieve the product by its ID
        $product = Product::find($id);
    
        // Check if the product exists
        if(!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        // Update the quantity
        $product->quantity = $validatedData['quantity'];
    
        // Save the updated product
        $product->save();
    
        // Return a response indicating success
        return response()->json(['message' => 'Product quantity updated successfully']);
    }
    
    
    public function getProductByBarcode(Request $request)
{
    $tag = $request->input('barcode'); // Retrieve the scanned tag (barcode)
    $product = Product::where('tag', $tag)->first(); // Query the Product table using the tag

    if ($product) {
        // Increment the quantity of the product by 1
        
        $product->save();

        return response()->json([
            'id' => $product->id, // Include the product ID
            'tag' => $product->tag,
            'product_name' => $product->product_name,
            'quantity' => $product->quantity,
            'price' => $product->price,
            'category' => $product->category,
            'brand' => $product->brand,
            'product_image' => asset('storage/product_images/' . $product->product_image)
        ]);
    } else {
        return response()->json(['error' => 'Product not found for the given barcode'], 404);
    }
}
    

    public function editProduct($id)
    {
        $product = Product::find($id);
    
        if ($product) {
            $product->product_image = asset('storage/product_images/' . $product->product_image);
            
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
    
    public function updateProduct(Request $request, $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'tag' => 'required|string',
            'product_name' => 'required|string',
            'category' => 'required|string',
            'brand' => 'required|string',
            'updated_by' => 'required|string',
            'description' => 'nullable|string', // Add validation for description
            'product_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules for the image
        ]);
    
        // Find the product by ID
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        // Update product data
        $product->quantity = $validatedData['quantity'];
        $product->price = $validatedData['price'];
        $product->tag = $validatedData['tag'];
        $product->product_name = $validatedData['product_name'];
        $product->updated_by = $validatedData['updated_by'];
        $product->category = $validatedData['category'];
        $product->brand = $validatedData['brand'];
        $product->description = $validatedData['description']; // Assign description to the product
    
        // Handle file upload for update
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/product_images', $imageName);
            
            // Delete the old product image if it exists
            if ($product->product_image) {
                Storage::disk('public')->delete('product_images/' . $product->product_image);
            }
    
            $product->product_image = $imageName;
        }
    
        $product->save();
    
        return response()->json(['message' => 'Product updated successfully']);
    }
    

    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if ($product) {
            // Delete the associated image file
            Storage::disk('public')->delete('product_images/' . $product->product_image);

            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function updateQuantities(Request $request)
    {
        // Retrieve data from the request
        $productNames = $request->input('product_names');
        $quantities = $request->input('quantities');

        // Check if both arrays are not null
        if ($productNames !== null && $quantities !== null) {
            // Update product quantities
            foreach ($productNames as $index => $productName) {
                // Use the correct table name and column name
                $product = Product::where('product_name', $productName)->first();

                if ($product) {
                    // Deduct the provided quantity from the current stock
                    $currentQuantity = $product->quantity;
                    $providedQuantity = $quantities[$index];

                    if ($currentQuantity >= $providedQuantity) {
                        $product->quantity = $currentQuantity - $providedQuantity;
                        $product->save();
                    } else {
                        // Handle the case where the provided quantity is greater than the current stock
                        Log::warning('Insufficient stock for product: ' . $productName);
                    }
                } else {
                    // Handle the case where the product is not found
                    Log::warning('Product not found: ' . $productName);
                }
            }

            return response()->json(['message' => 'Product quantities updated successfully']);
        } else {
            // Handle the case where product_names or quantities are null
            Log::error('Invalid request. Please provide product_names and quantities.');
            return response()->json(['error' => 'Invalid request. Please provide product_names and quantities.'], 400);
        }
    }

    public function uploadInventory(Request $request)
    {
        // Process the uploaded CSV file
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            // Parse and process the CSV file data
            // Example code to parse CSV and update database
            // You'll need to implement your own logic here
            // For example:
            $contents = file_get_contents($file->path());
            $rows = explode("\n", $contents);
            foreach ($rows as $row) {
                $data = str_getcsv($row);
                // Assuming CSV format: tag, name, category, brand, description, quantity, price
                $tag = $data[0];
                $quantity = $data[5];
                $product = Product::where('tag', $tag)->first();
                if ($product) {
                    // Product exists, update quantity
                    $product->quantity += $quantity;
                    $product->save();
                } else {
                    // Product doesn't exist, create new
                    Product::create([
                        'tag' => $tag,
                        'name' => $data[1],
                        'category' => $data[2],
                        'brand' => $data[3],
                        'description' => $data[4],
                        'quantity' => $quantity,
                        'price' => $data[6],
                    ]);
                }
            }
            return response()->json(['message' => 'Inventory updated successfully'], 200);
        } else {
            return response()->json(['error' => 'Invalid file or file not found'], 400);
        }
    }


}

