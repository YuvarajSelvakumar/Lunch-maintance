<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevisionController extends Controller
{
    
    public function history($type, $month)
    {
        // Map type to table name for safety & ease
        $tableMap = [
            'menu_pricing' => 'menu_pricings',
            'weekly_menu' => 'weekly_menus',
            'daily_lunch_entry' => 'daily_lunch_entries',
            'vendor_payment' => 'vendor_payments',
        ];

        if (!isset($tableMap[$type])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $table = $tableMap[$type];

        // Fetch revisions - assuming you have a revision table or audit trail (if you don't, you need to create one or log changes)
        // For demo, let's just fetch all records updated in the given month

        $revisions = DB::table($table)
            ->whereYear('updated_at', date('Y', strtotime($month)))
            ->whereMonth('updated_at', date('m', strtotime($month)))
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'type' => $type,
            'month' => $month,
            'revisions' => $revisions,
        ]);
    }

    // Show detailed revision report for a specific type and month
    public function report($type, $month)
    {
        $tableMap = [
            'menu_pricing' => 'menu_pricings',
            'weekly_menu' => 'weekly_menus',
            'daily_lunch_entry' => 'daily_lunch_entries',
            'vendor_payment' => 'vendor_payments',
        ];

        if (!isset($tableMap[$type])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $table = $tableMap[$type];

        // For demo: Fetch all revisions for the month with detailed info (could be extended with real audit log)
        $reports = DB::table($table)
            ->whereYear('updated_at', date('Y', strtotime($month)))
            ->whereMonth('updated_at', date('m', strtotime($month)))
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'type' => $type,
            'month' => $month,
            'reports' => $reports,
        ]);
    }
}
