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
        // 1. Define $month for both logic and Blade
        $month = $request->input('month', now()->format('Y-m'));
        $parsedMonth = Carbon::parse($month);

        // Enforce min allowed month (Jun 2025)
        $minAllowedMonth = Carbon::parse('2025-06-01');
        if ($parsedMonth->lt($minAllowedMonth)) {
            return redirect()->back()->withErrors(['month' => 'You cannot select a month before June 2025.']);
        }

        // Setup calendar view bounds
        $minMonthForView = $parsedMonth->format('Y-m');
        $maxMonthForView = $parsedMonth->copy()->addMonths(2)->format('Y-m');

        // Days of week mapping
        $days = [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday',
        ];

        // Load saved weekly menus
        $existingMenus = WeeklyMenu::whereYear('month', $parsedMonth->year)
            ->whereMonth('month', $parsedMonth->month)
            ->get()
            ->keyBy('day_of_week');

        // Generate calendar days
        $datesInMonth = [];
        $date = $parsedMonth->copy()->startOfMonth();
        $endDate = $parsedMonth->copy()->endOfMonth();
        while ($date->lte($endDate)) {
            $datesInMonth[] = $date->copy();
            $date->addDay();
        }

        // Compute per-day prices based on MenuPricing effective dates
        $dailyPrices = [];
        foreach ($datesInMonth as $date) {
    $dayName = $date->format('l');
    $menu = $existingMenus[$dayName] ?? null;

    if ($menu) {
        $mealTypeLower = strtolower($menu->meal_type);

        // Fetch pricing effective *on or before* this specific day
        $menuPricing = MenuPricing::whereDate('effective_from', '<=', $date->toDateString())
            ->orderByDesc('effective_from')
            ->first();

        $mealPrice = $menuPricing->{$mealTypeLower . '_price'} ?? null;
    } else {
        $mealPrice = null;
    }

    $dailyPrices[$date->format('Y-m-d')] = [
        'day_name'   => $dayName,
        'meal_type'  => $menu->meal_type ?? null,
        'meal_price' => $mealPrice,
    ];
}


        return view('weekly_menu.index', compact(
            'month',
            'days',
            'existingMenus',
            'datesInMonth',
            'dailyPrices',
            'minMonthForView',
            'maxMonthForView'
        )); // compact requires variables exist :contentReference[oaicite:1]{index=1}
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'meal'  => 'required|array',
        ]);

    $month = Carbon::createFromFormat('Y-m', $request->month);
$anyPricing = MenuPricing::whereDate('effective_from', '<=', $month->format('Y-m-d'))
    ->exists();

if (!$anyPricing) {
    return back()->withErrors([
        'month' => 'No pricing found for this month. Please add it in Menu Pricing.'
    ]);
}

        $maxAllowedMonth = Carbon::parse(now()->format('Y-m'))->addMonths(2)->endOfMonth();
        if ($month->copy()->gt($maxAllowedMonth)) {
            return back()->withErrors(['month' => 'You can only save menus from current month up to two months ahead.']);
        }

        // Find latest pricing effective on or before month start
        $pricing = MenuPricing::where('effective_from', '<=', $month->toDateString())
            ->orderByDesc('effective_from')
            ->first();

        if (!$pricing) {
            return back()->withErrors(['month' => 'No pricing found for this month. Please add it in Menu Pricing.']);
        }

        $dayMap = [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday',
        ];

        foreach ($request->meal as $short => $mealType) {
            $day = $dayMap[$short];
            $mealTypeLower = strtolower($mealType);
            $price = $pricing->{$mealTypeLower . '_price'} ?? 0;

            WeeklyMenu::updateOrCreate(
                [
                    'day_of_week' => $day,
                    'month'       => $month->toDateString(),
                ],
                [
                    'meal_type'  => $mealType,
                    'meal_price' => $price,
                ]
            );
        }

        return redirect()->route('weekly-menu.index', ['month' => $month->format('Y-m')])
                         ->with('success', 'Weekly menu saved successfully!');
    }
}
