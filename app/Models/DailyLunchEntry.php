<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLunchEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'meal_type',
        'meal_count',
        'meal_price',
        'total_cost',
        'pricing_version_id',
        'menu_version_id',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'meal_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    // Relationships
    public function menuPricing()
    {
        return $this->belongsTo(MenuPricing::class, 'pricing_version_id');
    }

    public function weeklyMenu()
    {
        return $this->belongsTo(WeeklyMenu::class, 'menu_version_id');
    }

    // Accessor for formatted price
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->meal_price, 2);
    }

    // Accessor for formatted total cost
    public function getFormattedTotalCostAttribute()
    {
        return '₹' . number_format($this->total_cost, 2);
    }
}