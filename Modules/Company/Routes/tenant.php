<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('company')->group(function () {
            //Route::get('/', [CompanyController::class, 'index']);
        });
    });
});
