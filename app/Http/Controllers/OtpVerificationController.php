<?php

namespace App\Http\Controllers;

use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $email = session('otp_email');
        $otpEntry = EmailOtp::where('email', $email)->first();

        if (!$otpEntry || $otpEntry->otp !== $request->otp || now()->gt($otpEntry->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ]);
        }

        $userData = session('pending_user_data');
        if (!$userData) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please register again.',
            ]);
        }

       $user = User::firstOrCreate(
    ['email' => $email],
    [
        'name' => $userData['name'],
        'password' => bcrypt($userData['password']), // hash it here only
        'email_verified_at' => now(),
    ]
);

        $user->email_verified_at = now();
        $user->save();

        // Clean up
        $otpEntry->delete();
        session()->forget(['otp_email', 'pending_user_data', 'last_otp_sent_at']);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified. Please log in.',
            'redirect' => route('login'),
        ]);
    }

    public function resend(Request $request)
    {
        $email = session('otp_email');
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found in session.',
            ]);
        }

        $cooldownSeconds = 30;
        $lastSent = session('last_otp_sent_at') ? now()->diffInSeconds(session('last_otp_sent_at')) : null;

        if ($lastSent && $lastSent < $cooldownSeconds) {
            $secondsLeft = max(1, $cooldownSeconds - $lastSent);
            return response()->json([
                'success' => false,
                'message' => "Please wait {$secondsLeft} seconds before resending.",
            ]);
        }

        $otp = rand(100000, 999999);

        EmailOtp::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        try {
            Mail::raw("Your OTP is: {$otp}", function ($message) use ($email) {
                $message->to($email)->subject('Your OTP Code');
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Try again later.',
            ]);
        }

        session(['last_otp_sent_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully.',
        ]);
    }
}
