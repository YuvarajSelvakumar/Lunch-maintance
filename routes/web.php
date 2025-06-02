<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuPricingController;
use App\Http\Controllers\WeeklyMenuController;
use App\Http\Controllers\MonthlySummaryController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\RevisionController;

// use App\Http\Controllers\MenuPricingController;

// Menu Pricing
Route::get('/', [MenuPricingController::class, 'index'])->name('menu-pricing.index');
Route::post('/', [MenuPricingController::class, 'store'])->name('menu-pricing.store');

// Weekly Menu
Route::get('/weekly-menu', [WeeklyMenuController::class, 'index'])->name('weekly-menu.index');
Route::post('/weekly-menu', [WeeklyMenuController::class, 'store'])->name('weekly-menu.store');

// Monthly Summary
Route::get('/monthly-summary', [MonthlySummaryController::class, 'index'])->name('monthly-summary.index');

// Vendor Payment
Route::get('/vendor-payment', [VendorPaymentController::class, 'index'])->name('vendor-payment.index');
Route::post('/vendor-payment', [VendorPaymentController::class, 'store'])->name('vendor-payment.store');

// Revision History
Route::get('/revision-history', [RevisionController::class, 'index'])->name('revision-history.index');

