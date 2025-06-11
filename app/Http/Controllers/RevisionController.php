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

        $startDate = Carbon::parse($month)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month)->endOfMonth()->toDateString();

        $revisions = collect();

        switch ($type) {
            case 'menu_pricing':
                $revisions = MenuPricing::whereBetween('effective_from', [$startDate, $endDate])
                    ->orderBy('effective_from', 'desc')
                    ->orderBy('version', 'desc')
                    ->get();
                break;

            case 'weekly_menu':
                $revisions = WeeklyMenu::whereBetween('month', [$startDate, $endDate])
                    ->orderBy('updated_at', 'desc')
                    ->get();
                break;

            case 'vendor_payment':
                $revisions = VendorPayment::where('month', $startDate)
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'daily_lunch_entry':
                $revisions = DailyLunchEntry::whereBetween('entry_date', [$startDate, $endDate])
                    ->orderBy('entry_date', 'desc')
                    ->get();
                break;
        }

        return view('revision-history.index', compact('revisions', 'type', 'month'));
    }
}
