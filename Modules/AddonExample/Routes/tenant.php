<?php

use Illuminate\Support\Facades\Route;

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('addonexample')->group(function() {
                Route::get('/', [AddonExampleController::class, 'index']);
            });
        });
    });
};