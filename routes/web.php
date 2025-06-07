<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuPricingController;
use App\Http\Controllers\WeeklyMenuController;
use App\Http\Controllers\DailyLunchEntryController;
use App\Http\Controllers\MonthlySummaryController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\RevisionController;

// use App\Http\Controllers\MenuPricingController;

// Menu Pricing
Route::get('/', [MenuPricingController::class, 'index'])->name('menu-pricing.index');
Route::post('/', [MenuPricingController::class, 'store'])->name('menu-pricing.store');

Route::get('/weekly-menu', [WeeklyMenuController::class, 'index'])->name('weekly-menu.index');
Route::post('/weekly-menu', [WeeklyMenuController::class, 'store'])->name('weekly-menu.store');
Route::get('/weekly-menu/{id}/edit', [WeeklyMenuController::class, 'edit'])->name('weekly-menu.edit');
Route::put('/weekly-menu/{id}', [WeeklyMenuController::class, 'update'])->name('weekly-menu.update');





Route::prefix('daily-lunch')->group(function () {
    Route::get('/', [DailyLunchEntryController::class, 'index'])->name('daily-lunch.index');      // list page
    Route::get('/create', [DailyLunchEntryController::class, 'create'])->name('daily-lunch.create');
    Route::post('/store', [DailyLunchEntryController::class, 'store'])->name('daily-lunch.store');
    Route::get('/get-meal-info', [DailyLunchEntryController::class, 'getMealInfo'])->name('daily-lunch.get-meal-info');
    Route::get('/daily-lunch/get-meal-info', [DailyLunchEntryController::class, 'getMealInfo'])->name('daily-lunch.get-meal-info');
    Route::get('/daily-lunch/{id}/edit', [DailyLunchEntryController::class, 'edit'])->name('daily-lunch.edit');
Route::put('/daily-lunch/{id}', [DailyLunchEntryController::class, 'update'])->name('daily-lunch.update');
Route::delete('/daily-lunch/{id}', [DailyLunchEntryController::class, 'destroy'])->name('daily-lunch.destroy');


});

Route::get('/daily-lunch/get-meal-info', [DailyLunchEntryController::class, 'getMealInfo'])->name('daily-lunch.get-meal-info');


// API for frontend dynamic fetch
Route::get('/api/meal-info', [DailyLunchEntryController::class, 'getMealInfo']);



// Monthly Summary
Route::get('/monthly-summary', [MonthlySummaryController::class, 'index'])->name('monthly-summary.index');
Route::get('monthly-summary/export-excel', [MonthlySummaryController::class, 'exportExcel'])->name('monthly-summary.exportExcel');
Route::get('monthly-summary/export-pdf', [MonthlySummaryController::class, 'exportPdf'])->name('monthly-summary.exportPdf');





// View payment summary page (with monthly summary values)
// Show payment for selected month
Route::get('/vendor-payment', [VendorPaymentController::class, 'index'])->name('vendor-payment.index');
Route::put('/vendor-payment/{vendorPayment}', [VendorPaymentController::class, 'update'])->name('vendor-payments.update');




// Revision History
Route::get('/revision-history', [RevisionController::class, 'index'])->name('revision-history.index');

