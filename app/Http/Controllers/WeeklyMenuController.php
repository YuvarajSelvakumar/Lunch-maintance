<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyMenu;
use Carbon\Carbon;

class WeeklyMenuController extends Controller
{
    public function index()
    {
        $weeklyMenus = WeeklyMenu::orderBy('month', 'desc')->get();
        return view('weekly_menu.index', compact('weeklyMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'meal_type' => 'required|in:Veg,Egg,Chicken',
            'effective_from' => 'required|date',
        ]);

        $monthDate = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();

        $latestVersion = WeeklyMenu::where('month', $monthDate)->max('version');
        $version = $latestVersion ? $latestVersion + 1 : 1;

        WeeklyMenu::create([
            'month' => $monthDate,
            'day_of_week' => $request->day_of_week,
            'meal_type' => $request->meal_type,
            'version' => $version,
            'effective_from' => $request->effective_from,
        ]);

        return redirect()->back()->with('success', 'Weekly menu saved successfully.');
    }
}
