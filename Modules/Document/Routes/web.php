<?php


use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {

        Route::get('downloads/{model}/{type}/{external_id}/{format?}', 'DownloadController@downloadExternal')->name('tenant.download.external_id');
        Route::get('print/{model}/{external_id}/{format?}', 'DownloadController@toPrint');
        Route::get('printticket/{model}/{external_id}/{format?}', 'DownloadController@toTicket');

        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('documents/not-sent')->group(function () {
                Route::get('', 'DocumentNotSentController@index')->name('tenant.documents.not_sent')->middleware('redirect.level', 'tenant.internal.mode');
                Route::get('records', 'DocumentNotSentController@records');
                Route::get('data_table', 'DocumentNotSentController@data_table');
            });

            Route::prefix('documents')->group(function () {
                Route::post('/categories', 'DocumentController@storeCategories');
                Route::post('/brands', 'DocumentController@storeBrands');
                Route::get('/search/customers', 'DocumentController@searchCustomers');
                Route::get('/search/customer/{id}', 'DocumentController@searchCustomerById');
                Route::get('/search/externalId/{external_id}', 'DocumentController@searchExternalId');

                Route::get('/', 'DocumentController@index')->name('tenant.documents.index')->middleware(['redirect.level', 'tenant.internal.mode']);
                Route::get('/columns', 'DocumentController@columns');
                Route::get('/records', 'DocumentController@records');
                Route::get('/recordsTotal', 'DocumentController@recordsTotal');
                Route::get('/create', 'DocumentController@create')->name('tenant.documents.create')->middleware(['redirect.level', 'tenant.internal.mode']);
                Route::get('/create_tensu', 'DocumentController@create_tensu')->name('tenant.documents.create_tensu');
                Route::get('/{id}/edit', 'DocumentController@edit')->middleware(['redirect.level', 'tenant.internal.mode']);
                Route::get('/{id}/show', 'DocumentController@show');

                Route::get('/tables', 'DocumentController@tables');
                Route::get('/record/{document}', 'DocumentController@record');
                Route::post('/', 'DocumentController@store');
                Route::post('/{id}/update', 'DocumentController@update');
                Route::get('/send/{document}', 'DocumentController@send');
                // Route::get('/remove/{document}', 'DocumentController@remove');
                // Route::get('/consult_cdr/{document}', 'DocumentController@consultCdr');
                Route::post('/email', 'DocumentController@email');
                Route::get('/note/{document}', 'NoteController@create');
                Route::get('/note/record/{document}', 'NoteController@record');
                Route::get('/item/tables', 'DocumentController@item_tables');
                Route::get('/table/{table}', 'DocumentController@table');
                Route::get('/re_store/{document}', 'DocumentController@reStore');
                Route::get('/locked_emission', 'DocumentController@messageLockedEmission');
                Route::get('/note/has-documents/{document}', 'NoteController@hasDocuments');

                Route::get('/send_server/{document}/{query?}', 'DocumentController@sendServer');
                Route::get('/check_server/{document}', 'DocumentController@checkServer');
                Route::get('/change_to_registered_status/{document}', 'DocumentController@changeToRegisteredStatus');

                Route::post('/import', 'DocumentController@import');
                Route::post('/import_second_format', 'DocumentController@importTwoFormat');
                Route::get('/data_table', 'DocumentController@data_table');
                Route::get('/payments/excel/{month}/{anulled}', 'DocumentController@report_payments')->name('tenant.document.payments.excel');
                Route::get('/payments-complete', 'DocumentController@report_payments');

                Route::post('/import_excel_format', 'DocumentController@importExcelFormat');
                Route::get('/import_excel_tables', 'DocumentController@importExcelTables');
                Route::delete('/delete_document/{document_id}', 'DocumentController@destroyDocument');
                Route::get('/data-table/items', 'DocumentController@getDataTableItem');
                Route::get('/retention/{document}', 'DocumentController@retention');
                Route::post('/retention', 'DocumentController@retentionStore');
                Route::post('/retention/upload', 'DocumentController@retentionUpload');

                Route::post('pay-constancy/upload', 'DocumentController@uploadPayConstancy');
                Route::post('pay-constancy/save', 'DocumentController@savePayConstancy');
                Route::get('detraction/tables', 'DocumentController@detractionTables');
                Route::get('data-table/customers', 'DocumentController@dataTableCustomers');
                Route::get('prepayments/{type}', 'DocumentController@prepayments');
                Route::get('search-items', 'DocumentController@searchItems'); // movido a App\Http\Controllers\Controller.php
                Route::get('search/item/{item}', 'DocumentController@searchItemById');
                Route::get('consult_cdr/{document}', 'DocumentController@consultCdr');

                Route::get('item-lots', 'DocumentController@searchLots');
                Route::get('regularize-lots/{document_item_id}', 'DocumentController@regularizeLots');

                Route::post('force-send-by-summary', 'DocumentController@forceSendBySummary');
                Route::post('item_lots', 'DocumentController@searchItemLots');

                Route::get('create/{table?}/{table_id?}', 'DocumentController@tableToDocument');

            });

            Route::prefix('document_payments')->group(function () {
                Route::get('/records/{document_id}', 'DocumentPaymentController@records');
                Route::get('/document/{document_id}', 'DocumentPaymentController@document');
                Route::get('/tables', 'DocumentPaymentController@tables');
                Route::post('/', 'DocumentPaymentController@store');
                Route::delete('/{document_payment}', 'DocumentPaymentController@destroy');
                Route::get('/initialize_balance', 'DocumentPaymentController@initialize_balance');
                Route::get('/report/{start}/{end}/{report}', 'DocumentPaymentController@report');
            });


            Route::prefix('reports/validate-documents')->group(function () {

                Route::get('', 'ValidateDocumentController@index')->name('tenant.validate_documents.index')->middleware('tenant.internal.mode');
                Route::get('records', 'ValidateDocumentController@records');
                Route::get('data_table', 'ValidateDocumentController@data_table');
                Route::post('regularize', 'ValidateDocumentController@regularize');

                // apiperu
                // rutas de consulta de validacion desde listado de comprobantes
                Route::get('validate_masivo', 'ValidateApiDocumentController@validate_masivo');
                Route::get('validateDocumentstxt', 'ValidateApiDocumentController@validateDocumentsTxt');
                Route::get('validatecount', 'ValidateApiDocumentController@countdocumennt');

            });

            Route::prefix('documents/regularize-shipping')->group(function () {
                Route::get('', 'DocumentRegularizeShippingController@index')->name('tenant.documents.regularize_shipping');
                Route::get('records', 'DocumentRegularizeShippingController@records');
                Route::get('data_table', 'DocumentRegularizeShippingController@data_table');

            });

            Route::get('contingencies', 'ContingencyController@index')->name('tenant.contingencies.index')->middleware('redirect.level', 'tenant.internal.mode');
            Route::get('contingencies/columns', 'ContingencyController@columns');
            Route::get('contingencies/records', 'ContingencyController@records');
            Route::get('contingencies/create', 'ContingencyController@create')->name('tenant.contingencies.create');
        });
    });
}
