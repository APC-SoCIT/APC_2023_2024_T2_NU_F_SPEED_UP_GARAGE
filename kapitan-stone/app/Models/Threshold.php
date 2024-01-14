<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    protected $table = 'thresholds'; // Adjust the table name as needed
    protected $primaryKey = 'id'; // Adjust the primary key as needed
    protected $fillable = ['value'];
}
