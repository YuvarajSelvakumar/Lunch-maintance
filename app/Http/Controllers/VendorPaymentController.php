<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorPayment;
use App\Models\MenuPricing;
use App\Models\DailyLunchEntry;
use Carbon\Carbon;

class VendorPaymentController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('Y-m-01'));
        $monthDate = Carbon::parse($month)->startOfMonth();

        // Get all lunch entries for the selected month
        $entries = DailyLunchEntry::whereMonth('entry_date', $monthDate->month)
                                  ->whereYear('entry_date', $monthDate->year)
                                  ->get();

        if ($entries->isEmpty()) {
            return back()->withErrors(['month' => 'No Daily Lunch Entries found for selected month.']);
        }

        // Get pricing for selected month
        $pricing = MenuPricing::whereMonth('month', $monthDate->month)
                              ->whereYear('month', $monthDate->year)
                              ->orderByDesc('version')
                              ->first();

        if (!$pricing) {
            return back()->withErrors(['month' => 'Menu Pricing not found for selected month.']);
        }

        // Count meals
        $totalVeg = $entries->where('meal_type', 'Veg')->sum('count');
        $totalEgg = $entries->where('meal_type', 'Egg')->sum('count');
        $totalChicken = $entries->where('meal_type', 'Chicken')->sum('count');

        $calculatedCost = ($totalVeg * $pricing->veg_price) +
                          ($totalEgg * $pricing->egg_price) +
                          ($totalChicken * $pricing->chicken_price);

        // Find or create vendor payment entry
        $payment = VendorPayment::firstOrCreate(
            ['month' => $monthDate],
            ['total_amount' => $calculatedCost, 'balance' => $calculatedCost]
        );

        // Always update amount & balance from calculation
        $payment->total_amount = $calculatedCost;
        $payment->balance = max(0, $payment->total_amount - $payment->paid_amount);
        $payment->status = $payment->balance <= 0 ? 'Fully Paid' : 'Partially Paid';
        $payment->save();

        // Get all payments to calculate outstanding balance
        $allPayments = VendorPayment::orderBy('month')->get();
        $totalBalance = $allPayments->sum('balance');

        return view('vendor-payment.index', compact('payment', 'month', 'totalBalance', 'allPayments'));
    }

    public function update(Request $request, VendorPayment $vendorPayment)
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        $remainingBalance = $vendorPayment->total_amount - $vendorPayment->paid_amount;

        if ($validated['paid_amount'] > $remainingBalance) {
            return redirect()->back()->withErrors([
                'paid_amount' => "Paid amount cannot exceed remaining balance (₹" . number_format($remainingBalance, 2) . ")."
            ])->withInput();
        }

        $vendorPayment->paid_amount += $validated['paid_amount'];
        $vendorPayment->payment_date = $validated['payment_date'];
        $vendorPayment->balance = max(0, $vendorPayment->total_amount - $vendorPayment->paid_amount);
        $vendorPayment->status = $vendorPayment->balance <= 0 ? 'Fully Paid' : 'Partially Paid';
        $vendorPayment->save();

        return redirect()->back()->with('success', 'Vendor payment updated.');
    }
}
