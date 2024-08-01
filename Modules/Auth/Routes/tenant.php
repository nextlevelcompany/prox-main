<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('get_data', [AuthController::class, 'getData']);
            //Route::get('/', [AuthController::class, 'index']);
        });
    });
});
