<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('hotel')->group(function () {
            //Route::get('/', [HotelController::class, 'index']);
        });
    });
});
