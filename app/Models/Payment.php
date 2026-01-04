<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'provider',
        'amount',
        'currency',
        'status',
        'transaction_id',
    ];
}
