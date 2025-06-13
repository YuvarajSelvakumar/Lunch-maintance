<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class RegistereduserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // ✅ Store user input in session
       session([
    'otp_email' => $validated['email'],
    'pending_user_data' => [
        'name' => $validated['name'],
        'password' => $validated['password'], // plain text!
    ],
    'last_otp_sent_at' => now(),
]);

        // ✅ Generate and store OTP
        $otp = rand(100000, 999999);

        EmailOtp::updateOrCreate(
            ['email' => $validated['email']],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        // ✅ Send OTP
        Mail::raw("Your OTP is: $otp", function ($message) use ($validated) {
            $message->to($validated['email'])->subject('OTP Verification');
        });

        return redirect()->route('verify.otp.form');
    }
}
