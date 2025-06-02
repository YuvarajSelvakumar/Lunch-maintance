<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyMenu extends Model
{
    protected $fillable = [
        'month',
        'day_of_week',
        'meal_type',
        'version',
        'effective_from',
    ];
}

