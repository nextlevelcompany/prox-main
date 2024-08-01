<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {

        Route::get('sale-notes/print/{external_id}/{format?}', 'SaleNoteController@toPrint');

        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('sale-notes')->group(function () {
                Route::get('/', 'SaleNoteController@index')->name('tenant.sale_notes.index')->middleware('redirect.level');
                Route::get('/columns', 'SaleNoteController@columns');
                Route::get('/columns2', 'SaleNoteController@columns2');
                Route::get('/records', 'SaleNoteController@records');
                Route::get('/totals', 'SaleNoteController@totals');
                Route::get('/create/{salenote?}', 'SaleNoteController@create')->name('tenant.sale_notes.create')->middleware('redirect.level');
                Route::get('/tables', 'SaleNoteController@tables');
                Route::post('/UpToOther', 'SaleNoteController@EnviarOtroSitio');
                Route::post('/getUpToOther', 'SaleNoteController@getSaleNoteToOtherSite');
                Route::post('/urlUpToOther', 'SaleNoteController@getSaleNoteToOtherSiteUrl');
                Route::post('/duplicate', 'SaleNoteController@duplicate');
                Route::get('/table/{table}', 'SaleNoteController@table');
                Route::post('/', 'SaleNoteController@store');
                Route::get('/record/{salenote}', 'SaleNoteController@record');
                Route::get('/item/tables', 'SaleNoteController@item_tables');
                Route::get('/search/customers', 'SaleNoteController@searchCustomers');
                Route::get('/search/customer/{id}', 'SaleNoteController@searchCustomerById');
                Route::get('/record2/{salenote}', 'SaleNoteController@record2');
                Route::get('/option/tables', 'SaleNoteController@option_tables');
                Route::get('/changed/{salenote}', 'SaleNoteController@changed');
                Route::post('/email', 'SaleNoteController@email');
                Route::get('/print-a5/{sale_note_id}/{format}', 'SaleNotePaymentController@toPrint');
                Route::get('/dispatches', 'SaleNoteController@dispatches');
                Route::delete('/destroy_sale_note_item/{sale_note_item}', 'SaleNoteController@destroy_sale_note_item');
                Route::get('/search-items', 'SaleNoteController@searchItems');
                Route::get('/search/item/{item}', 'SaleNoteController@searchItemById');
                Route::get('/list-by-client', 'SaleNoteController@saleNotesByClient');
                Route::post('/items', 'SaleNoteController@getItemsFromNotes');
                Route::get('/config-group-items', 'SaleNoteController@getConfigGroupItems');
                Route::post('/enabled-concurrency', 'SaleNoteController@enabledConcurrency');
                Route::get('/anulate/{id}', 'SaleNoteController@anulate');
                Route::get('/downloadExternal/{external_id}/{format?}', 'SaleNoteController@downloadExternal');
                Route::post('/transform-data-order', 'SaleNoteController@transformDataOrder');
                Route::post('/items-by-ids', 'SaleNoteController@getItemsByIds');
                Route::post('/delete-relation-invoice', 'SaleNoteController@deleteRelationInvoice');
                Route::get('/dispatch/{id}', 'SaleNoteController@recordsDispatch');
                Route::post('/dispatch', 'SaleNoteController@recordDispatch');
                Route::post('/dispatch/statusUpdate', 'SaleNoteController@statusUpdate');
                Route::delete('/dispatch/delete/{id}', 'SaleNoteController@destroyStatus');
                Route::get('/dispatch_note/{id}', 'SaleNoteController@recordsDispatchNote');
            });

            Route::prefix('sale_note_payments')->group(function () {
                Route::get('/records/{sale_note}', 'SaleNotePaymentController@records');
                Route::get('/document/{sale_note}', 'SaleNotePaymentController@document');
                Route::get('/tables', 'SaleNotePaymentController@tables');
                Route::post('/', 'SaleNotePaymentController@store');
                Route::delete('/{sale_note_payment}', 'SaleNotePaymentController@destroy');
            });
        });
    });
}
