<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('restaurant')->group(function () {
            //Route::get('/', [RestaurantController::class, 'index']);
        });
    });
});
