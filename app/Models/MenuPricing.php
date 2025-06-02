<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPricing extends Model
{
    protected $fillable = [
        'month',
        'veg_price',
        'egg_price',
        'chicken_price',
        'version',
        'effective_from'
    ];

    protected $casts = [
        'month' => 'date',
        'effective_from' => 'date',
    ];
}
