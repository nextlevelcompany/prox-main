<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('get_data', [AuthController::class, 'getData']);
    });
});
