<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    


    // ✅ Relationship: one VendorPayment has many VendorPaymentEntry
    public function entries(): HasMany
    {
        return $this->hasMany(VendorPaymentEntry::class);
    }

    // Optional status helpers
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isPartiallyPaid(): bool
    {
        return $this->status === 'Partially Paid';
    }

    public function isFullyPaid(): bool
    {
        return $this->status === 'Fully Paid';
    }

    // ✅ Optional: Calculate total paid from entries
    public function totalPaid(): float
    {
        return $this->entries()->sum('paid_amount');
    }
        
}

