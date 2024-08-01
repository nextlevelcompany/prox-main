<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('persons')->group(function () {
                Route::get('/columns', 'PersonController@columns');
                Route::get('/tables', 'PersonController@tables');
                Route::get('/{type}', 'PersonController@index')->name('tenant.persons.index');
                Route::get('/{type}/records', 'PersonController@records');
                Route::get('/record/{person}', 'PersonController@record');
                Route::post('', 'PersonController@store');
                Route::delete('/{person}', 'PersonController@destroy');
                Route::post('/import', 'PersonController@import');
                Route::get('/enabled/{type}/{person}', 'PersonController@enabled');
                Route::get('/{type}/exportation', 'PersonController@export')->name('tenant.persons.export');
                Route::get('/export/barcode/print', 'PersonController@printBarCode')->name('tenant.persons.export.barcode.print');
                Route::get('/barcode/{item}', 'PersonController@generateBarcode');
                Route::get('/search/{barcode}', 'PersonController@getPersonByBarcode');
                Route::get('accumulated-points/{id}', 'PersonController@getAccumulatedPoints');
                Route::get('search-data/{type}', 'PersonController@searchData');

            });

            Route::prefix('person-types')->group(function () {
                Route::get('/columns', 'PersonTypeController@columns');
                Route::get('', 'PersonTypeController@index')->name('tenant.person_types.index');
                Route::get('/records', 'PersonTypeController@records');
                Route::get('/record/{person}', 'PersonTypeController@record');
                Route::post('/', 'PersonTypeController@store');
                Route::delete('/{person}', 'PersonTypeController@destroy');
            });

            Route::get('customers/list', 'PersonController@clientsForGenerateCPE');

        });
    });
}
