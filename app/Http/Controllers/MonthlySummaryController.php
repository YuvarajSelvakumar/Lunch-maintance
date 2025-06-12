<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLunchEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlySummaryExport;
use PDF;

class MonthlySummaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth()->toDateString();
        $end = Carbon::parse($month)->endOfMonth()->toDateString();

        $entries = DailyLunchEntry::whereBetween('entry_date', [$start, $end])->get();

        $types = ['veg', 'egg', 'chicken'];
        $summary = [];
        foreach ($types as $type) {
            $summary[$type] = [
                'total_count' => $entries->where('meal_type', $type)->sum('meal_count'),
                'total_cost' => $entries->where('meal_type', $type)->sum('total_cost'),
            ];
        }
        $summary['total_cost'] = collect($summary)->sum('total_cost');

        // ✅ FIXED: replaced 'count' with 'meal_count'
        $allMonthlyEntries = DailyLunchEntry::selectRaw("
                DATE_FORMAT(entry_date, '%Y-%m') as month,
                meal_type,
                SUM(meal_count) as total_count,
                SUM(total_cost) as total_cost
            ")
            ->where('entry_date', '<=', $end)
            ->groupBy(DB::raw("DATE_FORMAT(entry_date, '%Y-%m')"), 'meal_type')
            ->orderBy('month', 'desc')
            ->get();

        $monthlySummaries = [];
        foreach ($allMonthlyEntries as $entry) {
            $monthlySummaries[$entry->month][$entry->meal_type] = [
                'total_count' => $entry->total_count,
                'total_cost' => $entry->total_cost,
            ];
        }

        $availableMonths = DailyLunchEntry::selectRaw('DATE_FORMAT(entry_date, "%Y-%m") as month')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('month');

        return view('monthly_summary.index', [
            'summary' => $summary,
            'month' => $month,
            'message' => $entries->isEmpty() ? 'No lunch entries found for this month.' : null,
            'monthlySummaries' => $monthlySummaries,
            'availableMonths' => $availableMonths,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth()->toDateString();
        $end = Carbon::parse($month)->endOfMonth()->toDateString();

        $entries = DailyLunchEntry::whereBetween('entry_date', [$start, $end])->get();

        // ✅ FIXED: replaced 'count' with 'meal_count'
        $summary = [
            'total_veg' => $entries->where('meal_type', 'veg')->sum('meal_count'),
            'total_veg_cost' => $entries->where('meal_type', 'veg')->sum('total_cost'),
            'total_egg' => $entries->where('meal_type', 'egg')->sum('meal_count'),
            'total_egg_cost' => $entries->where('meal_type', 'egg')->sum('total_cost'),
            'total_chicken' => $entries->where('meal_type', 'chicken')->sum('meal_count'),
            'total_chicken_cost' => $entries->where('meal_type', 'chicken')->sum('total_cost'),
            'total_cost' => $entries->sum('total_cost'),
        ];

        return Excel::download(new MonthlySummaryExport($summary, $month), "monthly_summary_{$month}.xlsx");
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth()->toDateString();
        $end = Carbon::parse($month)->endOfMonth()->toDateString();

        $entries = DailyLunchEntry::whereBetween('entry_date', [$start, $end])->get();

        // ✅ FIXED: replaced 'count' with 'meal_count'
        $summary = [
            'total_veg' => $entries->where('meal_type', 'veg')->sum('meal_count'),
            'total_veg_cost' => $entries->where('meal_type', 'veg')->sum('total_cost'),
            'total_egg' => $entries->where('meal_type', 'egg')->sum('meal_count'),
            'total_egg_cost' => $entries->where('meal_type', 'egg')->sum('total_cost'),
            'total_chicken' => $entries->where('meal_type', 'chicken')->sum('meal_count'),
            'total_chicken_cost' => $entries->where('meal_type', 'chicken')->sum('total_cost'),
            'total_cost' => $entries->sum('total_cost'),
        ];

        $pdf = PDF::loadView('monthly_summary.pdf', [
            'summary' => $summary,
            'month' => $month,
        ])->setPaper('a4', 'portrait');

        $fileName = "monthly_summary_{$month}.pdf";

        return $pdf->download($fileName);
    }
}
