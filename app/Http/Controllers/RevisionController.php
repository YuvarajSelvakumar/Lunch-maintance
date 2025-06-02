<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLunchEntry;
use App\Models\MenuPricing;
use App\Models\WeeklyMenu;
use App\Models\VendorPayment;

class RevisionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'menu_pricing'); // example: menu_pricing, weekly_menu, vendor_payment
        $month = $request->input('month', date('Y-m-01'));

        $revisions = collect();

        switch ($type) {
            case 'menu_pricing':
                $revisions = MenuPricing::where('month', $month)->orderBy('version', 'desc')->get();
                break;
            case 'weekly_menu':
                $revisions = WeeklyMenu::where('month', $month)->orderBy('version', 'desc')->get();
                break;
            case 'vendor_payment':
                $revisions = VendorPayment::where('month', $month)->get();
                break;
        }

        return view('revision-history.index', compact('revisions', 'type', 'month'));
    }
}
