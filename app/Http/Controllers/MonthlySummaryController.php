<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLunchEntry;
use App\Models\MenuPricing;
use Carbon\Carbon;
use App\Exports\MonthlySummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class MonthlySummaryController extends Controller
{
    public function index(Request $request)
    {
        // Step 1: Get selected month or default to current month
        $month = $request->input('month', now()->format('Y-m'));
        $monthStart = Carbon::parse($month)->startOfMonth();
        $monthEnd = Carbon::parse($month)->endOfMonth();

        // Step 2: Get all DailyLunchEntry entries for the selected month
        $entries = DailyLunchEntry::whereBetween('entry_date', [$monthStart, $monthEnd])->get();

        // Step 3: Calculate total counts per meal type
        $totalVeg = $entries->where('meal_type', 'Veg')->sum('count');
        $totalEgg = $entries->where('meal_type', 'Egg')->sum('count');
        $totalChicken = $entries->where('meal_type', 'Chicken')->sum('count');

        // Step 4: Get latest pricing for the month (by month only)
        $pricing = MenuPricing::whereMonth('month', $monthStart->month)
                              ->whereYear('month', $monthStart->year)
                              ->orderByDesc('version')
                              ->first();

        if (!$pricing) {
            return back()->withErrors(['month' => 'Menu pricing not found for selected month.']);
        }

        // Step 5: Calculate total cost
        $totalCost = ($totalVeg * $pricing->veg_price) +
                     ($totalEgg * $pricing->egg_price) +
                     ($totalChicken * $pricing->chicken_price);

        // Step 6: Prepare summary to pass to the view
        $summary = [
            'total_veg' => $totalVeg,
            'total_egg' => $totalEgg,
            'total_chicken' => $totalChicken,
            'total_cost' => $totalCost,
        ];

        // Step 7: Pass data to the view
        return view('monthly-summary.index', compact('summary', 'month', 'pricing'));
    }


public function exportExcel(Request $request)
{
    $month = $request->input('month', now()->format('Y-m'));
    $monthStart = Carbon::parse($month)->startOfMonth();
    $monthEnd = Carbon::parse($month)->endOfMonth();

    // Fetch your summary and pricing as before...
    $entries = DailyLunchEntry::whereBetween('entry_date', [$monthStart, $monthEnd])->get();

    $totalVeg = $entries->where('meal_type', 'Veg')->sum('count');
    $totalEgg = $entries->where('meal_type', 'Egg')->sum('count');
    $totalChicken = $entries->where('meal_type', 'Chicken')->sum('count');

    $pricing = MenuPricing::whereMonth('month', $monthStart->month)
                          ->whereYear('month', $monthStart->year)
                          ->orderByDesc('version')
                          ->first();

    $totalCost = ($totalVeg * $pricing->veg_price) +
                 ($totalEgg * $pricing->egg_price) +
                 ($totalChicken * $pricing->chicken_price);

    $summary = [
        'total_veg' => $totalVeg,
        'total_egg' => $totalEgg,
        'total_chicken' => $totalChicken,
        'total_cost' => $totalCost,
    ];

    return Excel::download(new MonthlySummaryExport($summary, $pricing, $month), 'monthly_summary.xlsx');
}


public function exportPdf(Request $request)
{
    $month = $request->input('month', now()->format('Y-m'));
    $monthStart = Carbon::parse($month)->startOfMonth();
    $monthEnd = Carbon::parse($month)->endOfMonth();

    $entries = DailyLunchEntry::whereBetween('entry_date', [$monthStart, $monthEnd])->get();

    $totalVeg = $entries->where('meal_type', 'Veg')->sum('count');
    $totalEgg = $entries->where('meal_type', 'Egg')->sum('count');
    $totalChicken = $entries->where('meal_type', 'Chicken')->sum('count');

    $pricing = MenuPricing::whereMonth('month', $monthStart->month)
                          ->whereYear('month', $monthStart->year)
                          ->orderByDesc('version')
                          ->first();

    if (!$pricing) {
        return back()->withErrors(['month' => 'Menu pricing not found for selected month.']);
    }

    $totalCost = ($totalVeg * $pricing->veg_price) +
                 ($totalEgg * $pricing->egg_price) +
                 ($totalChicken * $pricing->chicken_price);

    $summary = [
        'total_veg' => $totalVeg,
        'total_egg' => $totalEgg,
        'total_chicken' => $totalChicken,
        'total_cost' => $totalCost,
    ];

    $pdf = PDF::loadView('monthly-summary.pdf', compact('summary', 'month', 'pricing'));

    return $pdf->download("monthly_summary_{$month}.pdf");
}

}
