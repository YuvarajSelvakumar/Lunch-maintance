<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLunchEntry;


class MonthlySummaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('Y-m-01')); 
        

        
        $entries = DailyLunchEntry::whereYear('entry_date', date('Y', strtotime($month)))
                                 ->whereMonth('entry_date', date('m', strtotime($month)))
                                 ->get();

        $summary = [
            'total_veg' => $entries->sum('veg_count'),
            'total_egg' => $entries->sum('egg_count'),
            'total_chicken' => $entries->sum('chicken_count'),
            'total_cost' => $entries->sum('cost_calculated'),
        ];

        return view('monthly-summary.index', compact('summary', 'month'));
    }
}
