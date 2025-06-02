<?php

namespace App\Http\Controllers\Api;
namespace App\Models;
use Illuminate\Http\Request;
use App\Models\DailyLunchEntry;
use App\Models\MenuPricing;
use App\Models\WeeklyMenu;


use Illuminate\Database\Eloquent\Model;

class DailyLunchEntry extends Model

{
    // Save daily lunch entry
    public function store(Request $request)
    {
        // Validate inputs
        $request->validate([
            'entry_date' => 'required|date',
            'meal_type' => 'required|in:Veg,Egg,Chicken',
            'veg_count' => 'required|integer|min:0',
            'egg_count' => 'required|integer|min:0',
            'chicken_count' => 'required|integer|min:0',
        ]);

        // Extract month from entry_date (e.g. "2025-06-02" => "2025-06-01")
        $month = date('Y-m-01', strtotime($request->entry_date));

        // Get latest menu pricing version for the month
        $pricing = MenuPricing::where('month', $month)
                              ->orderByDesc('version')
                              ->first();

        if (!$pricing) {
            return response()->json(['error' => 'Menu pricing not found for this month'], 404);
        }

        // Get latest weekly menu version for the month
        $weeklyMenu = WeeklyMenu::where('month', $month)
                               ->orderByDesc('version')
                               ->first();

        if (!$weeklyMenu) {
            return response()->json(['error' => 'Weekly menu not found for this month'], 404);
        }

        // Calculate total cost: sum of (count * price) for all meal types
        $totalCost = 0;
        $totalCost += $request->veg_count * $pricing->veg_price;
        $totalCost += $request->egg_count * $pricing->egg_price;
        $totalCost += $request->chicken_count * $pricing->chicken_price;

        // Save daily lunch entry
        $entry = DailyLunchEntry::create([
            'entry_date' => $request->entry_date,
            'meal_type' => $request->meal_type,
            'veg_count' => $request->veg_count,
            'egg_count' => $request->egg_count,
            'chicken_count' => $request->chicken_count,
            'cost_calculated' => $totalCost,
            'pricing_version_id' => $pricing->id,
            'menu_version_id' => $weeklyMenu->id,
        ]);

        return response()->json(['message' => 'Lunch entry saved', 'data' => $entry], 201);
    }

    // Summary of lunch entries by month
    public function summary($month)
    {
        // Get all lunch entries for the month
        $entries = DailyLunchEntry::whereYear('entry_date', date('Y', strtotime($month)))
                                  ->whereMonth('entry_date', date('m', strtotime($month)))
                                  ->get();

        // Prepare summary data
        $summary = [
            'total_veg' => $entries->sum('veg_count'),
            'total_egg' => $entries->sum('egg_count'),
            'total_chicken' => $entries->sum('chicken_count'),
            'total_cost' => $entries->sum('cost_calculated'),
        ];

        return response()->json($summary);
    }
}
