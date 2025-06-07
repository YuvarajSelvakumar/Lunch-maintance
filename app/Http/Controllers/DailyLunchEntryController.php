<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLunchEntry;
use App\Models\WeeklyMenu;
use App\Models\MenuPricing;
use Carbon\Carbon;

class DailyLunchEntryController extends Controller
{
    public function index()
{
    $entries = \App\Models\DailyLunchEntry::orderBy('entry_date', 'desc')->get();
    return view('daily_lunch.index', compact('entries'));
}
   public function create()
{
    $entries = DailyLunchEntry::orderBy('entry_date', 'desc')->get();
    return view('daily_lunch.index', compact('entries'));
}
public function store(Request $request)
{
    $validated = $request->validate([
        'entry_date' => 'required|date',
        'day_name' => 'required|string',
        'meal_type' => 'required|string',
        'meal_price' => 'required|numeric',
        'count' => 'required|integer|min:1',
        'total_cost' => 'required|numeric',
    ]);

    $existingEntry = DailyLunchEntry::where('entry_date', $validated['entry_date'])->first();

    if ($existingEntry) {
        return redirect()->route('daily-lunch.create')
            ->with('error', 'An entry for this date already exists.');
    }

    DailyLunchEntry::create($validated);

    return redirect()->route('daily-lunch.create')->with('success', 'Lunch entry saved successfully.');
}

    public function getMealInfo(Request $request)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $carbonDate = Carbon::parse($date);
        $dayName = $carbonDate->format('l'); // Full day name
        $month = $carbonDate->format('Y-m');

        $weeklyMenu = WeeklyMenu::where('month', $carbonDate->startOfMonth()->format('Y-m-d'))
                                ->where('day_of_week', $dayName)
                                ->first();

        if (!$weeklyMenu) {
            return response()->json([
                'day_name' => $dayName,
                'meal_type' => null,
                'price' => null,
            ]);
        }

        $mealType = $weeklyMenu->meal_type;
        $priceColumn = strtolower($mealType) . '_price';

        $menuPricing = MenuPricing::whereYear('month', $carbonDate->year)
                                  ->whereMonth('month', $carbonDate->month)
                                  ->orderByDesc('version')
                                  ->first();

        $price = ($menuPricing && isset($menuPricing->{$priceColumn})) ? $menuPricing->{$priceColumn} : null;

        return response()->json([
            'day_name' => $dayName,
            'meal_type' => $mealType,
            'price' => $price,
        ]);
    }
    public function edit($id)
{
    $entry = DailyLunchEntry::findOrFail($id);
    $entries = DailyLunchEntry::orderBy('entry_date', 'desc')->get();
    return view('daily_lunch.edit', compact('entry', 'entries'));
}
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'entry_date' => 'required|date',
        'day_name' => 'required|string',
        'meal_type' => 'required|string',
        'meal_price' => 'required|numeric',
        'count' => 'required|integer|min:1',
        'total_cost' => 'required|numeric',
    ]);

    // Check if selected date already exists (excluding current record)
    $duplicate = DailyLunchEntry::where('entry_date', $request->entry_date)
                    ->where('id', '!=', $id)
                    ->exists();

    if ($duplicate) {
        return redirect()->back()->with('error', 'Selected date already exists.');
    }

    // Update the entry
    DailyLunchEntry::where('id', $id)->update($validated);

    return redirect()->route('daily-lunch.create')->with('success', 'Lunch entry updated successfully.');
}


public function destroy($id)
{
    DailyLunchEntry::destroy($id);

    return redirect()->route('daily-lunch.create')->with('success', 'Lunch entry deleted successfully.');
}

}
