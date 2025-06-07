<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyMenu extends Model
{
    protected $fillable = [
        'month',
        'day',
        'day_of_week',
        'meal_type',
        'meal_price',
        'version',
        'count',
        'total_price',

    ];
}

