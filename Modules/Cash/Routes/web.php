<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('cash')->group(function () {
                Route::get('/', 'CashController@index')->name('tenant.cash.index');
                Route::get('/columns', 'CashController@columns');
                Route::get('/records', 'CashController@records');
                Route::get('/create', 'CashController@create')->name('tenant.sale_notes.create');
                Route::get('/tables', 'CashController@tables');
                Route::get('/opening_cash', 'CashController@opening_cash');
                Route::get('/opening_cash_check/{user_id}', 'CashController@opening_cash_check');

                Route::post('/', 'CashController@store');
                Route::post('/cash_document', 'CashController@cash_document');
                Route::get('/close/{cash}', 'CashController@close');
                Route::get('/report/{cash}', 'CashController@report');
                Route::get('/report', 'CashController@report_general');

                Route::get('/record/{cash}', 'CashController@record');
                Route::delete('/{cash}', 'CashController@destroy');
                Route::get('/item/tables', 'CashController@item_tables');
                Route::get('/search/customers', 'CashController@searchCustomers');
                Route::get('/search/customer/{id}', 'CashController@searchCustomerById');

                Route::get('/report/products/{cash}/{is_garage?}', 'CashController@report_products');
                Route::get('/report/products-excel/{cash}', 'CashController@report_products_excel');
                Route::get('/report/cash-excel/{cash}', 'CashController@report_cash_excel');
            });
        });
    });
}
