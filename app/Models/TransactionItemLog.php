<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product; // Make sure to import the Product model if not already imported

class TransactionItemLog extends Model
{
    use HasFactory;

    protected $table = 'transaction_item_logs';

    protected $fillable = [
        'item_id', 
        'qty',
        'remarks', // Added remarks attribute
    ];

    // Specify the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }
}
