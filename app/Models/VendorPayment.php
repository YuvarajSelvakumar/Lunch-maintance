<?php

namespace App\Http\Controllers\Api;
namespace App\Models;
use Illuminate\Http\Request;

use App\Models\VendorPayment;
use Illuminate\Database\Eloquent\Model;
class VendorPayment extends Model
{
    // Show payment details for a given month
    public function show($month)
    {
        $payment = VendorPayment::where('month', $month)->first();

        if (!$payment) {
            return response()->json(['error' => 'No vendor payment found for this month'], 404);
        }

        return response()->json($payment);
    }

    // Save or update vendor payment
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'month' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'arrears' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
        ]);

        // Find existing payment record for the month or create new
        $payment = VendorPayment::updateOrCreate(
            ['month' => $request->month],
            [
                'total_amount' => $request->total_amount,
                'amount_paid' => $request->amount_paid,
                'arrears' => $request->arrears,
                'payment_date' => $request->payment_date,
            ]
        );

        return response()->json(['message' => 'Vendor payment saved successfully', 'data' => $payment], 201);
    }
}
