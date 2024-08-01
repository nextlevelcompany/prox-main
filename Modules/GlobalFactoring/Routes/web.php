<?php

use Illuminate\Support\Facades\Route;

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('globalfactoring')->group(function() {
                Route::post('/send-document', 'GlobalFactoringController@send');
                Route::post('/query-document', 'GlobalFactoringController@query');
            });
        });
    });
};