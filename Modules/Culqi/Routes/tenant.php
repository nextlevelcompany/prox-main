<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('culqi')->group(function () {
            //Route::get('/', [CulqiController::class, 'index']);
        });
    });
});
