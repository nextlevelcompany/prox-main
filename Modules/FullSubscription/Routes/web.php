<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)
        ->middleware(['redirect.level'])
        ->group(function () {
            Route::middleware(['auth', 'locked.tenant'])
                ->prefix('full_subscription')
                ->group(function () {
                    /**
                     * full_subscription/client
                     */
                    Route::prefix('client')->group(function () {
                        Route::get('/', 'ClientFullSubscriptionController@index')->name('tenant.fullsubscription.client.index');
                        Route::get('/childrens', 'ClientFullSubscriptionController@indexChildren')->name('tenant.fullsubscription.client_children.index');
                        Route::post('/', 'ClientFullSubscriptionController@store');

                        Route::get('/columns', 'ClientFullSubscriptionController@Columns');
                        Route::post('/records', 'ClientFullSubscriptionController@Records');
                        Route::post('/tables', 'ClientFullSubscriptionController@Tables');
                        Route::post('/record', 'ClientFullSubscriptionController@Record');
                        Route::post('/record/server', 'ClientFullSubscriptionController@RecordServer');


                    });
                    /**
                     * full_subscription/service
                     */
                    Route::prefix('service')->group(function () {
                        Route::get('/', 'ServiceFullSubscriptionController@index')
                            ->name('tenant.fullsubscription.service.index')
                            ->middleware(['redirect.level']);
                        /*

                        Route::get('/columns', 'ServiceFullSubscriptionController@Columns');
                        Route::post('/records', 'ServiceFullSubscriptionController@Records');
                        Route::post('/tables', 'ServiceFullSubscriptionController@Tables');
                        Route::post('/record', 'ServiceFullSubscriptionController@Record');
                        */
                    });
                    // items/export/barcode/last

                    /**
                     * full_subscription/plans
                     */
                    Route::prefix('plans')->group(function () {
                        Route::get('/', 'PlansFullSubscriptionController@index')
                            ->name('tenant.fullsubscription.plans.index')
                            ->middleware(['redirect.level']);
                        Route::post('/', 'PlansFullSubscriptionController@store');

                        Route::get('/columns', 'PlansFullSubscriptionController@Columns');
                        Route::post('/records', 'PlansFullSubscriptionController@Records');
                        Route::post('/tables', 'PlansFullSubscriptionController@Tables');
                        Route::post('/record', 'PlansFullSubscriptionController@Record');

                        Route::delete('/{id}', 'PlansFullSubscriptionController@destroy');

                    });

                    /**
                     * full_subscription/payments
                     */
                    Route::prefix('payments')->group(function () {

                        Route::get('/', 'PaymentsFullSubscriptionController@index')
                            ->name('tenant.fullsubscription.payments.index')
                            ->middleware(['redirect.level']);
                        Route::post('/', 'PaymentsFullSubscriptionController@store');

                        Route::get('/columns', 'PaymentsFullSubscriptionController@Columns');
                        Route::post('/records', 'PaymentsFullSubscriptionController@Records');
                        Route::post('/tables', 'PaymentsFullSubscriptionController@Tables');
                        Route::post('/record', 'PaymentsFullSubscriptionController@Record');
                        Route::post('/search/customers', 'PaymentsFullSubscriptionController@searchCustomer');

                    });
                    /**
                     * full_subscription/payment_receipt
                     */
                    Route::prefix('payment_receipt')->group(function () {
                        Route::get('/', 'PaymentReceiptFullSubscriptionController@index')
                            ->name('tenant.fullsubscription.payment_receipt.index');

                    });


                    Route::post('CommonData', 'FullSubscriptionController@Tables');
                });
        });
}
