<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MenuPricing extends Model
{
    protected $fillable = [
       
        'veg_price',
        'egg_price',
        'chicken_price',
        'version',
        'effective_from' 
        
    ];

    protected $casts = [
        
        'effective_from' => 'date',
    ];
    
}


