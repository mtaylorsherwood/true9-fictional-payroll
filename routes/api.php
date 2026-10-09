<?php

declare(strict_types=1);

use App\Http\Controllers\PaymentCalculatorController;
use Illuminate\Support\Facades\Route;

/*
 * This would usually be wrapped within the Sanctum middleware, to enforce authentication via an API token
 * But for the purposes of this test, we'll leave it open
 */
Route::post('/calculatePaymentDate', PaymentCalculatorController::class)->name('calculatePaymentDate');
