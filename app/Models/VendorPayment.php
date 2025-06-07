<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    protected $fillable = [
        'month',
        'total_amount',
        'paid_amount',
        'balance',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'month' => 'date',
        'payment_date' => 'date',
    ];
}
