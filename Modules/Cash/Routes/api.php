<?php

use Illuminate\Support\Facades\Route;


$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) 
{
    Route::domain($hostname->fqdn)->group(function() {

        Route::middleware(['auth:api', 'locked.tenant'])->group(function() {

            Route::post('cash/restaurant', 'Api\CashController@storeRestaurant');

        });

    });
}