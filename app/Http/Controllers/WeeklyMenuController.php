<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyMenu;
use App\Models\MenuPricing;
use Carbon\Carbon;

class WeeklyMenuController extends Controller
{
public function index(Request $request)
{
    $month = $request->input('month', now()->format('Y-m'));

    $days = [
        'mon' => 'Monday',
        'tue' => 'Tuesday',
        'wed' => 'Wednesday',
        'thu' => 'Thursday',
        'fri' => 'Friday',
        'sat' => 'Saturday',
        'sun' => 'Sunday',
    ];

    $parsedMonth = Carbon::parse($month);

    // Fetch existing weekly menus for the selected month keyed by day_of_week
    $existingMenus = WeeklyMenu::whereYear('month', $parsedMonth->year)
                               ->whereMonth('month', $parsedMonth->month)
                               ->get()
                               ->keyBy('day_of_week');

    // Get latest menu pricing for that month
    $pricing = MenuPricing::whereYear('month', $parsedMonth->year)
                         ->whereMonth('month', $parsedMonth->month)
                         ->orderByDesc('version')
                         ->first();

    return view('weekly_menu.index', compact('month', 'days', 'existingMenus', 'pricing'));
}

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'meal' => 'required|array',
        ]);

        $month = Carbon::createFromFormat('Y-m', $request->month);

        $pricing = MenuPricing::whereYear('month', $month->year)
                              ->whereMonth('month', $month->month)
                              ->orderByDesc('version')
                              ->first();

        foreach ($request->meal as $day => $mealType) {
            $price = 0;
            if ($pricing) {
                $mealTypeLower = strtolower($mealType);
                $priceKey = "{$mealTypeLower}_price";
                $price = $pricing->$priceKey ?? 0;
            }

            WeeklyMenu::updateOrCreate(
                [
                    'day_of_week' => $day,
                    'month' => $month->format('Y-m-01'),
                ],
                [
                    'meal_type' => $mealType,
                    'meal_price' => $price,
                ]
            );
        }

        return redirect()->route('weekly-menu.index', ['month' => $month->format('Y-m')])
                         ->with('success', 'Weekly menu saved successfully!');
    }
}
