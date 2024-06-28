<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentConfig\App\Http\Controllers\PaymentConfigController;

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
Route::group(['middleware' => ['auth','verified']], function () {

    Route::prefix('paymentconfig')->group(function () {
        Route::get('/', [PaymentConfigController::class, 'index'])->name('paymentconfig.index');
        Route::post('/', [PaymentConfigController::class, 'store'])->name('paymentconfig.store');
        // Add more routes as needed
    });
});
// Route::resource('paymentconfigs', PaymentConfigController::class);
