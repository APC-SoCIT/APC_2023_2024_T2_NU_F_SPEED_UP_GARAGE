<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POS extends Model
{
    use HasFactory;
    protected $fillable = [
        'tag', 'product_name', 'category', 'brand', 'quantity', 'price', 'product_image',
    ];
}

