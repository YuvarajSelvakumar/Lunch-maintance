<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLunchEntry;
use App\Models\MenuPricing;
use App\Models\WeeklyMenu;
use Carbon\Carbon;

class DailyLunchEntryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($month));

        $entries = DailyLunchEntry::whereBetween('entry_date', [$startDate, $endDate])
                                  ->orderBy('entry_date', 'asc')
                                  ->get();

        return view('daily_lunch.index', compact('entries', 'month'));
    }

    public function create()
    {
        return view('daily_lunch.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'meal_count' => 'required|integer|min:0',
        ]);

        $date = Carbon::parse($request->entry_date);
        $dayName = $date->format('l');

        // Find weekly menu for the selected date
        $weeklyMeal = WeeklyMenu::where('day_of_week', $dayName)
                                ->whereYear('month', $date->year)
                                ->whereMonth('month', $date->month)
                                ->first();

        if (!$weeklyMeal) {
            return back()->withErrors(['meal' => 'No meal plan found for ' . $dayName . ' in ' . $date->format('M Y')])->withInput();
        }

        $mealType = strtolower($weeklyMeal->meal_type);

        // Find pricing effective on or before the selected date
        $pricing = MenuPricing::whereDate('effective_from', '<=', $date->toDateString())
                              ->orderByDesc('effective_from')
                              ->first();

        if (!$pricing) {
            return back()->withErrors(['meal' => 'No pricing data found for ' . $date->format('d M Y')])->withInput();
        }

        // Get meal price based on meal type
        $mealPrice = $pricing->{$mealType . '_price'};
        $count = $request->meal_count;
        $totalCost = $mealPrice * $count;

        // Create the entry with all required fields
        DailyLunchEntry::create([
            'entry_date' => $request->entry_date,
            'meal_type' => $mealType,
            'meal_count' => $count,
            'meal_price' => $mealPrice, // Store individual meal price
            'total_cost' => $totalCost,
            'pricing_version_id' => $pricing->id,
            'menu_version_id' => $weeklyMeal->id,
        ]);

        return redirect()->route('daily-lunch.index', ['month' => $date->format('Y-m')])
                         ->with('success', 'Entry added successfully. Total cost: ₹' . number_format($totalCost, 2));
    }

    public function getMealInfo(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);
        $dayName = $date->format('l');

        // Find weekly menu for the selected date
        $weeklyMeal = WeeklyMenu::where('day_of_week', $dayName)
                                ->whereYear('month', $date->year)
                                ->whereMonth('month', $date->month)
                                ->first();

        if (!$weeklyMeal) {
            return response()->json([
                'error' => 'No meal plan found for ' . $dayName . ' in ' . $date->format('M Y'),
            ], 404);
        }

        $mealType = strtolower($weeklyMeal->meal_type);

        // Find pricing effective on or before the selected date
        $pricing = MenuPricing::whereDate('effective_from', '<=', $date->toDateString())
                              ->orderByDesc('effective_from')
                              ->first();

        if (!$pricing) {
            return response()->json([
                'error' => 'No pricing data found for ' . $date->format('d M Y'),
            ], 404);
        }

        // Get meal price based on meal type
        $mealPrice = $pricing->{$mealType . '_price'};

        return response()->json([
            'day' => $dayName,
            'meal_type' => $mealType,
            'price' => number_format($mealPrice, 2),
            'price_raw' => $mealPrice,
            'pricing_version' => $pricing->version ?? 'N/A',
            'pricing_effective_from' => $pricing->effective_from,
        ]);
    }

    public function edit($id)
    {
        $entry = DailyLunchEntry::findOrFail($id);
        return view('daily_lunch.edit', compact('entry'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'meal_count' => 'required|integer|min:0',
        ]);

        $entry = DailyLunchEntry::findOrFail($id);

        // Get the original pricing used for this entry
        $pricing = MenuPricing::find($entry->pricing_version_id);

        if (!$pricing) {
            return back()->withErrors(['meal' => 'Original pricing data not found for this entry.']);
        }

        // Get meal price based on meal type (use stored meal_price if pricing not found)
        $mealPrice = $pricing ? $pricing->{$entry->meal_type . '_price'} : $entry->meal_price;
        $count = $request->meal_count;
        $totalCost = $mealPrice * $count;

        $entry->update([
            'meal_count' => $count,
            'meal_price' => $mealPrice, // Update meal price in case pricing changed
            'total_cost' => $totalCost,
        ]);

        return redirect()->route('daily-lunch.index')->with('success', 'Entry updated successfully. New total cost: ₹' . number_format($totalCost, 2));
    }

    public function destroy($id)
    {
        $entry = DailyLunchEntry::findOrFail($id);
        $entry->delete();

        return redirect()->route('daily-lunch.index')->with('success', 'Entry deleted successfully.');
    }
}