<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuPricingController;
use App\Http\Controllers\WeeklyMenuController;
use App\Http\Controllers\DailyLunchEntryController;
use App\Http\Controllers\MonthlySummaryController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\OtpVerificationController;

// 🌐 Public welcome page
Route::get('/', function () {
    return view('welcome');
});

// ✅ Fix Breeze login redirect to dashboard
Route::get('/dashboard', function () {
    return redirect()->route('menu-pricing.index');
})->middleware(['auth'])->name('dashboard');

// 🛡️ Authenticated routes
Route::middleware(['auth', 'otp.verified'])->group(function () {
    // All your protected routes like dashboard, menu-pricing, etc.
});

Route::middleware('auth')->group(function () {

    // 🔐 Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 📋 Menu Pricing
    Route::get('/menu-pricing', [MenuPricingController::class, 'index'])->name('menu-pricing.index');
    Route::post('/menu-pricing', [MenuPricingController::class, 'store'])->name('menu-pricing.store');

    // 📆 Weekly Menu
    Route::get('/weekly-menu', [WeeklyMenuController::class, 'index'])->name('weekly-menu.index');
    Route::post('/weekly-menu', [WeeklyMenuController::class, 'store'])->name('weekly-menu.store');
    Route::get('/weekly-menu/{id}/edit', [WeeklyMenuController::class, 'edit'])->name('weekly-menu.edit');
    Route::put('/weekly-menu/{id}', [WeeklyMenuController::class, 'update'])->name('weekly-menu.update');

    // 🍽️ Daily Lunch Entry
    Route::prefix('daily-lunch')->group(function () {
        Route::get('/', [DailyLunchEntryController::class, 'index'])->name('daily-lunch.index');
        Route::get('/create', [DailyLunchEntryController::class, 'create'])->name('daily-lunch.create');
        Route::post('/store', [DailyLunchEntryController::class, 'store'])->name('daily-lunch.store');
        Route::get('/meal-info', [DailyLunchEntryController::class, 'getMealInfo'])->name('daily-lunch.get-meal-info');
        Route::get('/{id}/edit', [DailyLunchEntryController::class, 'edit'])->name('daily-lunch.edit');
        Route::put('/{id}', [DailyLunchEntryController::class, 'update'])->name('daily-lunch.update');
        Route::delete('/{id}', [DailyLunchEntryController::class, 'destroy'])->name('daily-lunch.destroy');
    });

    // 📊 Monthly Summary
    Route::get('/monthly-summary', [MonthlySummaryController::class, 'index'])->name('monthly-summary.index');
    Route::get('/monthly-summary/export-excel', [MonthlySummaryController::class, 'exportExcel'])->name('monthly-summary.exportExcel');
    Route::get('/monthly-summary/export-pdf', [MonthlySummaryController::class, 'exportPdf'])->name('monthly-summary.exportPdf');

    // 💰 Vendor Payment
    Route::get('/vendor-payment', [VendorPaymentController::class, 'index'])->name('vendor-payment.index');
    Route::post('/vendor-payment/{vendorPayment}/update', [VendorPaymentController::class, 'storePaymentEntry'])->name('vendor-payment.update');
    Route::get('/vendor-payment/refresh', [VendorPaymentController::class, 'refresh'])->name('vendor-payment.refresh');

    // 📜 Revision History
    Route::get('/revision-history', [RevisionController::class, 'index'])->name('revision-history.index');
});
Route::get('/verify-otp', function () {
    return view('auth.verify-otp');
})->name('verify.otp.form');

Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])->name('resend.otp');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
require __DIR__.'/auth.php';

