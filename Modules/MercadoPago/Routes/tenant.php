<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('mercadopago')->group(function () {
            //Route::get('/', [MercadoPagoController::class, 'index']);
        });
    });
});