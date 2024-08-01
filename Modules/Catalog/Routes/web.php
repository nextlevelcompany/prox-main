<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('unit_types')->group(function () {
                Route::get('/records', 'UnitTypeController@records');
                Route::get('/record/{code}', 'UnitTypeController@record');
                Route::post('/', 'UnitTypeController@store');
                Route::delete('/{code}', 'UnitTypeController@destroy');
            });

            Route::prefix('transfer-reason-types')->group(function () {
                Route::get('/records', 'TransferReasonTypeController@records');
                Route::get('/record/{code}', 'TransferReasonTypeController@record');
                Route::post('/', 'TransferReasonTypeController@store');
                Route::delete('/{code}', 'TransferReasonTypeController@destroy');
            });

            Route::prefix('detraction_types')->group(function () {
                Route::get('/records', 'DetractionTypeController@records');
                Route::get('/tables', 'DetractionTypeController@tables');
                Route::get('/record/{code}', 'DetractionTypeController@record');
                Route::post('/', 'DetractionTypeController@store');
                Route::delete('/{code}', 'DetractionTypeController@destroy');
            });

            Route::prefix('currency_types')->group(function () {
                Route::get('/records', 'CurrencyTypeController@records');
                Route::get('/record/{currency_type}', 'CurrencyTypeController@record');
                Route::post('/', 'CurrencyTypeController@store');
                Route::delete('/{currency_type}', 'CurrencyTypeController@destroy');
            });

            Route::prefix('tribute_concept_types')->group(function () {
                Route::get('/records', 'TributeConceptTypeController@records');
                Route::get('/record/{id}', 'TributeConceptTypeController@record');
                Route::post('/', 'TributeConceptTypeController@store');
                Route::delete('/{id}', 'TributeConceptTypeController@destroy');
            });

            Route::prefix('payment_method')->group(function () {
                Route::get('/records', 'PaymentMethodTypeController@records');
                Route::get('/record/{code}', 'PaymentMethodTypeController@record');
                Route::post('/', 'PaymentMethodTypeController@store');
                Route::delete('/{code}', 'PaymentMethodTypeController@destroy');
            });

            Route::prefix('card_brands')->group(function () {
                Route::get('records', 'CardBrandController@records');
                Route::get('record/{card_brand}', 'CardBrandController@record');
                Route::post('/', 'CardBrandController@store');
                Route::delete('{card_brand}', 'CardBrandController@destroy');
            });
        });
    });
}
