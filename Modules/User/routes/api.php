<?php

use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\Api\UserController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::post('register', [UserController::class, 'store']);
Route::post('login', [UserController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    // Route::apiResource('user', UserController::class)->names('user');
});
