<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\InventoryLog;

class DailyInventoryLogTask extends Command
{
    protected $signature = 'daily:inventory-log';

    protected $description = 'Insert records from products table into inventory_logs table daily';

    public function handle()
    {
        // Get products from the products table
        $products = Product::all();

        // Insert records into the inventory_logs table
        foreach ($products as $product) {
            InventoryLog::create([
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'quantity' => $product->quantity,
                'created_at' => now(), // Assuming you want to set the created_at timestamp to the current time
            ]);            
        }

        $this->info('Inventory logs inserted successfully.');
    }
}
