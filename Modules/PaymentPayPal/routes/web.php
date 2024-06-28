<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentPayPal\App\Http\Controllers\PaymentPayPalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('paymentpaypal')->group(function () {
    Route::get('/', [PaymentPayPalController::class, 'index'])->name('paymentpaypal.index');
    // Add more routes as needed
});