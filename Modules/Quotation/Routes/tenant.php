<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('quotation')->group(function () {
            //Route::get('/', [QuotationController::class, 'index']);
        });
    });
});
