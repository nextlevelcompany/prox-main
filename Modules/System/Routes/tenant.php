<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('system')->group(function () {
            //Route::get('/', [SystemController::class, 'index']);
        });
    });
});
