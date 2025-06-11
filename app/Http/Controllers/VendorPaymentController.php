<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorPayment;
use App\Models\VendorPaymentEntry;
use App\Models\DailyLunchEntry;
use Carbon\Carbon;

class VendorPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Get all months from DailyLunchEntry in format Y-m-01
        $availableMonths = DailyLunchEntry::selectRaw('DATE_FORMAT(entry_date, "%Y-%m-01") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        // Default to current month if none selected
        $monthInput = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($monthInput)->startOfMonth();
        $end = Carbon::parse($monthInput)->endOfMonth();

        // Find or create VendorPayment record
        $payment = VendorPayment::firstOrCreate(
            ['month' => $start->toDateString()],
            ['total_amount' => 0, 'paid_amount' => 0, 'balance' => 0, 'status' => 'Pending']
        );

        // Calculate total cost for the month
        $totalCost = DailyLunchEntry::whereBetween('entry_date', [$start, $end])->sum('total_cost');
        $paymentEntries = $payment->entries()->orderBy('payment_date', 'desc')->get();

        $paid = $paymentEntries->sum('paid_amount');
        $payment->total_amount = $totalCost;
        $payment->paid_amount = $paid;
        $payment->balance = max(0, $totalCost - $paid);
        $payment->payment_date = $paymentEntries->max('payment_date');

        if ($paid == 0) {
            $payment->status = 'Pending';
        } elseif ($payment->balance == 0) {
            $payment->status = 'Fully Paid';
        } else {
            $payment->status = 'Partially Paid';
        }

        $payment->save();

        $allPayments = VendorPayment::orderBy('month', 'desc')->get();
        $totalBalance = VendorPayment::sum('balance');

        return view('vendor_payment.index', compact(
            'monthInput', 'payment', 'paymentEntries',
            'availableMonths', 'allPayments', 'totalBalance'
        ));
    }

    public function storePaymentEntry(Request $request, VendorPayment $vendorPayment)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        $newPaidAmount = $request->input('paid_amount');
        $currentPaid = $vendorPayment->entries()->sum('paid_amount');

        if ($currentPaid + $newPaidAmount > $vendorPayment->total_amount) {
            return redirect()->back()->with('error', 'Paid amount cannot exceed total amount.');
        }

        VendorPaymentEntry::create([
            'vendor_payment_id' => $vendorPayment->id,
            'paid_amount' => $newPaidAmount,
            'payment_date' => $request->input('payment_date'),
        ]);

        return redirect()->route('vendor-payment.index', [
            'month' => Carbon::parse($vendorPayment->month)->format('Y-m')
        ])->with('success', 'Payment entry added successfully.');
    }

    public function refresh(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $payment = VendorPayment::firstOrCreate(['month' => $start->toDateString()]);

        $totalCost = DailyLunchEntry::whereBetween('entry_date', [$start, $end])->sum('total_cost');
        $paid = $payment->entries()->sum('paid_amount');

        $payment->update([
            'total_amount' => $totalCost,
            'paid_amount' => $paid,
            'balance' => max(0, $totalCost - $paid),
            'payment_date' => $payment->entries()->max('payment_date'),
            'status' => $paid == 0 ? 'Pending' : ($totalCost - $paid == 0 ? 'Fully Paid' : 'Partially Paid'),
        ]);

        return redirect()->route('vendor-payment.index', ['month' => $month])
                         ->with('success', 'Vendor payment refreshed.');
    }
}
