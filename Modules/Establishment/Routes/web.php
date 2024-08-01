<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('establishments')->group(function () {
                Route::get('/', 'EstablishmentController@index')->name('tenant.establishments.index');
                Route::get('/create', 'EstablishmentController@create');
                Route::get('/tables', 'EstablishmentController@tables');
                Route::get('/record/{establishment}', 'EstablishmentController@record');
                Route::post('/', 'EstablishmentController@store');
                Route::get('/records', 'EstablishmentController@records');
                Route::delete('/{establishment}', 'EstablishmentController@destroy');
            });

            Route::prefix('series')->group(function () {
                Route::get('/records/{establishment}/{document_type?}', 'SeriesController@records');
                Route::get('/create', 'SeriesController@create');
                Route::get('/tables', 'SeriesController@tables');
                Route::post('/', 'SeriesController@store');
                Route::delete('/{series}', 'SeriesController@destroy');
            });

            Route::prefix('series-configurations')->group(function () {
                Route::get('', 'SeriesConfigurationController@index')->name('tenant.series_configurations.index')->middleware('redirect.level');
                Route::get('records', 'SeriesConfigurationController@records');
                Route::get('tables', 'SeriesConfigurationController@tables');
                Route::post('', 'SeriesConfigurationController@store');
                Route::delete('{record}', 'SeriesConfigurationController@destroy');
            });
        });
    });
}
