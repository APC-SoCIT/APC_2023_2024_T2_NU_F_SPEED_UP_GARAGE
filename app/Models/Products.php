<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    // Specify the casts for attributes
    protected $casts = [
        'price' => 'float',
    ];

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'tag', 'product_name', 'category', 'brand', 'quantity', 'price', 'updated_by', 'description', 'product_image', 'product_image_path'
    ];
}
