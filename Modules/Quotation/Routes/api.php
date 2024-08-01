<?php

use Illuminate\Support\Facades\Route;

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) 
{
    Route::domain($hostname->fqdn)->group(function() {

        Route::middleware(['auth:api', 'locked.tenant'])->group(function() {

            Route::prefix('quotations')->group(function () {
                Route::get('tables', 'Api\QuotationController@tables');
            });

        });

    });
}
