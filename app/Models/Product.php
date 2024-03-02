<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Specify the casts for attributes
    protected $casts = [
        'price' => 'float',
    ];

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'id', 'tag', 'product_name', 'category', 'brand', 'quantity', 'brand_id', 'quantity_id', 'price', 'updated_by', 'description', 'product_image', 'product_image_path'
    ];
}
