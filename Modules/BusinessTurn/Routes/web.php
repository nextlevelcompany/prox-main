<?php

use Illuminate\Support\Facades\Route;

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function() {
            Route::prefix('business_turns')->group(function () {
                Route::get('tables', 'BusinessTurnController@tables');
                Route::get('records', 'BusinessTurnController@records');
                Route::post('validate_hotel', 'BusinessTurnController@validate_hotel');
                Route::post('', 'BusinessTurnController@store');
                Route::get('', 'BusinessTurnController@index')->name('tenant.business_turns.index')->middleware('redirect.level');
                Route::post('validate_transports', 'BusinessTurnController@validate_transports');
                Route::get('tables/transports', 'BusinessTurnController@tablesTransports');
                Route::post('validate_hotel_guest', 'BusinessTurnController@validate_hotel_guest');
            });
        });
    });
}
