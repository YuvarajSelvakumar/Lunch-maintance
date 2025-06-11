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
        $entries = DailyLunchEntry::orderBy('entry_date', 'desc')->get();
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
    $dateStr = $request->query('date');
    if (!$dateStr) {
        return response()->json(['error' => 'Date is required'], 400);
    }

    $date = Carbon::parse($dateStr);
    $dayName = $date->format('l'); // e.g. "Monday"

    // Fetch weekly menu for that day, regardless of month
    $weeklyMenu = WeeklyMenu::where('day_of_week', $dayName)->first();

    if (!$weeklyMenu) {
        return response()->json([
            'day_name'  => $dayName,
            'meal_type' => null,
            'price'     => null,
        ]);
    }

    
    $mealTypeLower = strtolower($weeklyMenu->meal_type);
    $priceColumn = "{$mealTypeLower}_price";

    $menuPricing = MenuPricing::whereDate('effective_from', '<=', $date->toDateString())
        ->orderByDesc('effective_from')
        ->first();

    return response()->json([
        'day_name'  => $dayName,
        'meal_type' => $weeklyMenu->meal_type,
        'price'     => $menuPricing->{$priceColumn} ?? null,
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

        $duplicate = DailyLunchEntry::where('entry_date', $request->entry_date)
                        ->where('id', '!=', $id)
                        ->exists();

        if ($duplicate) {
            return redirect()->back()->with('error', 'Selected date already exists.');
        }

        DailyLunchEntry::where('id', $id)->update($validated);

        return redirect()->route('daily-lunch.create')->with('success', 'Lunch entry updated successfully.');
    }

    public function destroy($id)
    {
        DailyLunchEntry::destroy($id);

        return redirect()->route('daily-lunch.create')->with('success', 'Lunch entry deleted successfully.');
    }
}
