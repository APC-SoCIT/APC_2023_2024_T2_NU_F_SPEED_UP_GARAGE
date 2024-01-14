<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id'; // Change 'transaction_id' to your actual primary key column name if different

    protected $fillable = [
        'customer_name',
        'phone',
        'date',
        'payment_total',
        'customer_change',
        'items',
        'qty',
        'quantity',
        'total_amount',
        'payment_method',
        'status',
        'cashier_name',
        // Add other fields as needed
    ];
}
