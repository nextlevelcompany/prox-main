<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::get('order-notes/print/{external_id}/{format?}', 'OrderNoteController@toPrint');
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            Route::prefix('order-notes')->group(function () {

                Route::get('/', 'OrderNoteController@index')->name('tenant.order_notes.index')->middleware(['redirect.level']);
                Route::get('columns', 'OrderNoteController@columns');
                Route::get('records', 'OrderNoteController@records');
                Route::get('create', 'OrderNoteController@create')->name('tenant.order_notes.create')->middleware(['redirect.level']);
                Route::get('edit/{id}', 'OrderNoteController@edit')->middleware(['redirect.level']);

                Route::get('tables', 'OrderNoteController@tables');
                Route::get('table/{table}', 'OrderNoteController@table');
                Route::post('/', 'OrderNoteController@store');
                Route::post('update', 'OrderNoteController@update');
                Route::get('record/{quotation}', 'OrderNoteController@record');
                Route::get('voided/{id}', 'OrderNoteController@voided');
                Route::get('item/tables', 'OrderNoteController@item_tables');
                Route::get('option/tables', 'OrderNoteController@option_tables');
                Route::get('search/customers', 'OrderNoteController@searchCustomers');
                Route::get('search/customer/{id}', 'OrderNoteController@searchCustomerById');
                Route::get('search-items', 'OrderNoteController@searchItems');
                Route::get('search/item/{item}', 'OrderNoteController@searchItemById');
                Route::get('download/{external_id}/{format?}', 'OrderNoteController@download');
                // Route::get('print/{external_id}/{format?}', 'OrderNoteController@toPrint');
                Route::post('email', 'OrderNoteController@email');
                Route::post('duplicate', 'OrderNoteController@duplicate');
                Route::get('record2/{quotation}', 'OrderNoteController@record2');
                Route::delete('destroy_order_note_item/{order_note_item}', 'OrderNoteController@destroy_order_note_item');
                Route::get('documents', 'OrderNoteController@documents');
                Route::post('documents', 'OrderNoteController@generateDocuments');
                Route::post('Quotation/get/{id}', 'OrderNoteController@getQuotationToOrderNote');
                Route::get('document_tables', 'OrderNoteController@document_tables');
                Route::post('import/MiTiendaPe', 'MiTiendaPeController@import');
                Route::post('update/state', 'OrderNoteController@updateState');
            });
        });
    });
}
