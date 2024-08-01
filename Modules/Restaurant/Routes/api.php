<?php

use Illuminate\Support\Facades\Route;

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);
if ($hostname) {
    Route::domain($hostname->fqdn)->group(function() {

        Route::middleware(['auth:api', 'locked.tenant'])->group(function() {

            Route::prefix('restaurant')->group(function () {
                Route::get('/items', 'RestaurantController@items');
                Route::post('/items/price', 'RestaurantController@savePrice');

                Route::get('/categories', 'RestaurantController@categories');
                Route::get('/configurations', 'RestaurantConfigurationController@record');
                Route::get('/waiters', 'WaiterController@records');
                Route::get('/tablesAndEnv', 'RestaurantConfigurationController@tablesAndEnv');
                Route::post('/table/{id}', 'RestaurantConfigurationController@saveTable');
                Route::get('/table/{id}', 'RestaurantConfigurationController@getTable');
                Route::get('/notes', 'NotesController@records');

            });

        });

    });
}
