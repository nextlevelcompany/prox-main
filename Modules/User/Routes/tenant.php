<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('user')->group(function () {
            //Route::get('/', [UserController::class, 'index']);
        });
    });
});
