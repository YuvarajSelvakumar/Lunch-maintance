<?php
use App\Http\Controllers\Api\MenuPricingController;
use App\Http\Controllers\Api\WeeklyMenuController;
use App\Http\Controllers\Api\LunchEntryController;
use App\Http\Controllers\Api\VendorPaymentController;
use App\Http\Controllers\Api\RevisionController;

Route::get('/menu-pricing/{month}', [MenuPricingController::class, 'show']);
Route::post('/menu-pricing', [MenuPricingController::class, 'store']);

Route::get('/weekly-menu/{month}', [WeeklyMenuController::class, 'show']);
Route::post('/weekly-menu', [WeeklyMenuController::class, 'store']);

Route::post('/lunch-entry', [LunchEntryController::class, 'store']);
Route::get('/lunch-summary/{month}', [LunchEntryController::class, 'summary']);

Route::get('/vendor-payment/{month}', [VendorPaymentController::class, 'show']);
Route::post('/vendor-payment', [VendorPaymentController::class, 'store']);

Route::get('/revisions/{type}/{month}', [RevisionController::class, 'history']);
Route::get('/revision-report/{type}/{month}', [RevisionController::class, 'report']);
