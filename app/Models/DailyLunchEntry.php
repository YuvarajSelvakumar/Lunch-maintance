<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLunchEntry extends Model
{
    protected $fillable = [
        'entry_date',
        'day_name',
        'meal_type',
        'veg_count',
        'egg_count',
        'chicken_count',
        'cost_calculated',
        'pricing_version_id',
        'menu_version_id',
         
        'day_of_week',
         'meal_type', 
         'price',
         'meal_price',
          'count',
         'total_cost',
    ];
}
