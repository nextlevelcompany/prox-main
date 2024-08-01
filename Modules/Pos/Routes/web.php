<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            Route::get('pos_full', 'PosController@index_full')->name('tenant.pos_full.index');

            Route::prefix('pos')->group(function() {
                Route::get('history-sales/records', 'HistoryController@recordsSales');
                Route::get('history-purchases/records', 'HistoryController@recordsPurchases');
                Route::get('/', 'PosController@index')->name('tenant.pos.index');
                Route::get('/search_items', 'PosController@search_items');
                Route::get('/tables', 'PosController@tables');
                Route::get('/table/{table}', 'PosController@table');
                Route::get('/payment_tables', 'PosController@payment_tables');
                Route::get('/payment', 'PosController@payment')->name('tenant.pos.payment');
                Route::get('/status_configuration', 'PosController@status_configuration');
                Route::get('/validate_stock/{item}/{quantity}', 'PosController@validate_stock');
                Route::get('/items', 'PosController@item');
                Route::get('/search_items_cat', 'PosController@search_items_cat');
                Route::get('/fast', 'PosController@fast')->name('tenant.pos.fast');
                Route::get('/garage', 'PosController@garage')->name('tenant.pos.garage');
            });

            Route::prefix('cash')->group(function() {

                /*
                 * cash/report-a4/{cash}
                 * cash/report-ticket/{cash}
                 * cash/report-excel/{cash}
                 * cash/email
                 */
                Route::get('report-a4/{cash}', 'CashController@reportA4');
                Route::get('report-ticket/{cash}/{format?}', 'CashController@reportTicket');
                Route::get('report-excel/{cash}', 'CashController@reportExcel');
                Route::post('email', 'CashController@email');
                Route::get('simple/report-a4/{cash}', 'CashController@reportSimpleA4');

                Route::get('report-cash-income-egress/{cash}', 'CashController@reportCashIncomeEgress');

            });


            Route::prefix('cash-reports')->group(function() {

                Route::get('summary-daily-operations/{cash_id}', 'CashReportController@reportSummaryDailyOperations');
                Route::get('payments-associated-cash/{cash_id}', 'CashReportController@reportPaymentsAssociatedCash');

                Route::get('general-with-payments/{cash_id}', 'CashReportController@generalCashReportWithPayments');

            });


        });
    });
}
