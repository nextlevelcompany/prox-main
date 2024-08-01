<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {

        Route::get('quotations/print/{external_id}/{format?}', 'QuotationController@toPrint');

        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('quotations')->group(function () {
                Route::get('/', 'QuotationController@index')->name('tenant.quotations.index')->middleware('redirect.level');
                Route::get('/columns', 'QuotationController@columns');
                Route::get('/records', 'QuotationController@records');
                Route::get('/create/{saleOpportunityId?}', 'QuotationController@create')->name('tenant.quotations.create')->middleware('redirect.level');
                Route::get('/edit/{id}', 'QuotationController@edit')->middleware('redirect.level');

                Route::get('/state-type/{state_type_id}/{id}', 'QuotationController@updateStateType');
                Route::get('/filter', 'QuotationController@filter');
                Route::get('/tables', 'QuotationController@tables');
                Route::get('/table/{table}', 'QuotationController@table');
                Route::post('/', 'QuotationController@store');
                Route::post('/update', 'QuotationController@update');
                Route::get('/record/{quotation}', 'QuotationController@record');
                Route::get('/anular/{id}', 'QuotationController@anular');
                Route::get('/item/tables', 'QuotationController@item_tables');
                Route::get('/option/tables', 'QuotationController@option_tables');
                Route::get('/search/customers', 'QuotationController@searchCustomers');
                Route::get('/search/customer/{id}', 'QuotationController@searchCustomerById');
                Route::get('/download/{external_id}/{format?}', 'QuotationController@download');
                Route::post('/email', 'QuotationController@email');
                Route::post('/duplicate', 'QuotationController@duplicate');
                Route::get('/record2/{quotation}', 'QuotationController@record2');
                Route::get('/changed/{quotation}', 'QuotationController@changed');

                Route::get('/search-items', 'QuotationController@searchItems');
                Route::get('/search/item/{item}', 'QuotationController@searchItemById');
                Route::get('/item-warehouses/{item}', 'QuotationController@itemWarehouses');
            });
        });
    });
}
