<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('voided')->group(function () {
                Route::get('/', 'VoidedController@index')->name('tenant.voided.index')->middleware('redirect.level', 'tenant.internal.mode');
                Route::get('/columns', 'VoidedController@columns');
                Route::get('/records', 'VoidedController@records');
                Route::post('/', 'VoidedController@store');
                Route::get('/status/{voided}', 'VoidedController@status');
                Route::get('/status_masive', 'VoidedController@status_masive');
                Route::delete('/{voided}', 'VoidedController@destroy');
            });
        });
    });
}
