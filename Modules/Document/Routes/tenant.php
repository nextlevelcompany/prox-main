<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('document')->group(function () {
            //Route::get('/', [DocumentController::class, 'index']);
        });
    });
});
