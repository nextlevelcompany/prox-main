<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('levelaccess')->group(function () {
            //Route::get('/', [LevelAccessController::class, 'index']);
        });
    });
});
