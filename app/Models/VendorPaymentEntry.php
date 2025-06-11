<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPaymentEntry extends Model
{
    protected $fillable = [
        'vendor_payment_id',
        'paid_amount',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function vendorPayment()
    {
        return $this->belongsTo(VendorPayment::class);
    }
    
}
