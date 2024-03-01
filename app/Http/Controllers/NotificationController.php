<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Threshold;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function checkNotifications()
    {
        try {
            // Fetch notifications
            $notifications = $this->fetchNotifications();
    
            // Log a message indicating successful notification fetching
            Log::info('Notifications fetched successfully');
    
            return response()->json($notifications);
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error('Error fetching notifications: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching notifications.'], 500);
        }
    }
    protected function fetchNotifications()
{
    $notifications = [];
    
    // Fetch the threshold value
    $threshold = Threshold::first();

    // Log the threshold value for debugging
    Log::debug("Threshold value: " . ($threshold ? $threshold->value : 'Not found'));

    // Fetch all products
    $products = Product::all();

    // Log the number of products fetched
    Log::info('Fetched ' . count($products) . ' products');

    // Check each product against the threshold value
    foreach ($products as $product) {
        // Log product and threshold values for debugging
        Log::debug("Product: {$product->product_name}, Quantity: {$product->quantity}, Threshold: " . ($threshold ? $threshold->value : 'Not found'));
    
        // Check if the product quantity is below or equal to the threshold value
        if ($threshold && $product->quantity <= $threshold->value && $product->quantity > 0) {
            // Log a notification for low stock
            Log::warning("Low stock for {$product->product_name}. Quantity: {$product->quantity}, Threshold: {$threshold->value}");
    
            // Add a notification for low stock
            $notifications[] = [
                'message' => "Low stock for {$product->product_name}. Current quantity is {$product->quantity}"
            ];
        } elseif ($product->quantity == 0) { // Corrected the comparison operator here
            // Log a notification for out of stock
            Log::warning("Out of stock for {$product->product_name}. Quantity: {$product->quantity}");
    
            // Add a notification for out of stock
            $notifications[] = [
                'message' => "Out of stock for {$product->product_name}. Current quantity is {$product->quantity}"
            ];
        }
    }
    

    // Log the number of notifications generated
    Log::info('Generated ' . count($notifications) . ' notifications');

    return $notifications;
}

}    