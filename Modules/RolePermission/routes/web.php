<?php

use Illuminate\Support\Facades\Route;
use Modules\RolePermission\app\Http\Controllers\RolePermissionController;
use Modules\RolePermission\app\Http\Controllers\RoleController;
use Modules\RolePermission\app\Http\Controllers\PermissionController;
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

    Route::middleware(['web', 'auth'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });
});

