<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorPayment;

class VendorPaymentController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('Y-m-01')); // Default current month

        $payment = VendorPayment::where('month', $month)->first();

        return view('vendor-payment.index', compact('payment', 'month'));
    }
}
