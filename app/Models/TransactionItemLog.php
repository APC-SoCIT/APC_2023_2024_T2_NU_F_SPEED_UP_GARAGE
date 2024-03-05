<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItemLog extends Model
{
    protected $table = 'transaction_item_logs';

    protected $fillable = [
        'item_id', 'qty'
    ];
}
