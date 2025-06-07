<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLunchEntry;
use App\Models\MenuPricing;
use App\Models\WeeklyMenu;
use App\Models\VendorPayment;
use Carbon\Carbon;

class RevisionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'menu_pricing');
        $month = $request->input('month', date('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth()->toDateString(); // e.g. '2025-06-01'
        $endDate = Carbon::parse($month)->endOfMonth()->toDateString(); // e.g. '2025-06-30'

        $revisions = collect();

        switch ($type) {
            case 'menu_pricing':
                // Assuming 'month' column is a date with first day of month
                $revisions = MenuPricing::where('month', $startDate)
                    ->orderBy('version', 'desc')
                    ->get();
                break;

            case 'weekly_menu':
                $revisions = WeeklyMenu::where('month', $startDate)
                    ->orderBy('version', 'desc')
                    ->get();
                break;

            case 'vendor_payment':
                $revisions = VendorPayment::where('month', $startDate)
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
case 'daily_lunch_entry':
    $startDate = $month . '-01';
    $endDate = date('Y-m-t', strtotime($month));
    $revisions = DailyLunchEntry::whereBetween('entry_date', [$startDate, $endDate])
                ->orderBy('entry_date', 'desc')->get();
    break;

        }

        return view('revision-history.index', compact('revisions', 'type', 'month'));
    }
}
