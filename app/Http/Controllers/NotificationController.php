<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Threshold;

class NotificationController extends Controller
{
    public function checkNotifications()
    {
        try {
            // Fetch notifications
            $notifications = $this->fetchNotifications();
    
            return response()->json($notifications);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching notifications.'], 500);
        }
    }
    
    protected function fetchNotifications()
    {
        $notifications = [];
        
        // Fetch the threshold value
        $threshold = Threshold::first();

        // Fetch all products
        $products = Product::all();

        // Check each product against the threshold value
        foreach ($products as $product) {
            // Check if the product quantity is below or equal to the threshold value
            if ($threshold && $product->quantity <= $threshold->value && $product->quantity > 0) {
                $notifications[] = [
                    'message' => "Low stock for {$product->product_name}. Current quantity is {$product->quantity}"
                ];
            } elseif ($product->quantity == 0) {
                $notifications[] = [
                    'message' => "Out of stock for {$product->product_name}. Current quantity is {$product->quantity}"
                ];
            }
        }

        return $notifications;
    }
}
