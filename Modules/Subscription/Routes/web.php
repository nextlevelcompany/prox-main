<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)
        ->middleware(['redirect.level'])
        ->group(function () {
            Route::middleware(['auth', 'locked.tenant'])
                ->prefix('subscription')
                ->group(function () {
                    Route::prefix('client')->group(function () {
                        Route::get('/', 'ClientSubscriptionController@index')->name('tenant.subscription.client.index');
                        Route::get('/childrens', 'ClientSubscriptionController@indexChildren')->name('tenant.subscription.client_children.index');
                        Route::post('/', 'ClientSubscriptionController@store');

                        Route::get('/columns', 'ClientSubscriptionController@Columns');
                        Route::post('/records', 'ClientSubscriptionController@Records');
                        Route::post('/tables', 'ClientSubscriptionController@Tables');
                        Route::post('/record', 'ClientSubscriptionController@Record');


                    });
                    /**
                     * subscription/service
                     */
                    Route::prefix('service')->group(function () {
                        Route::get('/', 'ServiceSubscriptionController@index')
                            ->name('tenant.subscription.service.index')
                            ->middleware(['redirect.level']);
                        /*

                        Route::get('/columns', 'ServiceSubscriptionController@Columns');
                        Route::post('/records', 'ServiceSubscriptionController@Records');
                        Route::post('/tables', 'ServiceSubscriptionController@Tables');
                        Route::post('/record', 'ServiceSubscriptionController@Record');
                        */
                    });
                    // items/export/barcode/last

                    /**
                     * subscription/plans
                     */
                    Route::prefix('plans')->group(function () {
                        Route::get('/', 'PlansSubscriptionController@index')
                            ->name('tenant.subscription.plans.index')
                            ->middleware(['redirect.level']);
                        Route::post('/', 'PlansSubscriptionController@store');

                        Route::get('/columns', 'PlansSubscriptionController@Columns');
                        Route::post('/records', 'PlansSubscriptionController@Records');
                        Route::post('/tables', 'PlansSubscriptionController@Tables');
                        Route::post('/record', 'PlansSubscriptionController@Record');

                        Route::delete('/{id}', 'PlansSubscriptionController@destroy');

                    });

                    /**
                     * subscription/payments
                     */
                    Route::prefix('payments')->group(function () {

                        /*
                        Route::get('/', 'SubscriptionController@payments_index')
                            ->name('tenant.subscription.payments.index')
                            ->middleware(['redirect.level']);
                        */

                        Route::get('/', 'PaymentsSubscriptionController@index')
                            ->name('tenant.subscription.payments.index')
                            ->middleware(['redirect.level']);
                        Route::post('/', 'PaymentsSubscriptionController@store');

                        Route::get('/columns', 'PaymentsSubscriptionController@Columns');
                        Route::post('/records', 'PaymentsSubscriptionController@Records');
                        Route::post('/tables', 'PaymentsSubscriptionController@Tables');
                        Route::post('/record', 'PaymentsSubscriptionController@Record');
                        Route::post('/search/customers', 'PaymentsSubscriptionController@searchCustomer');

                    });
                    /**
                     * subscription/payment_receipt
                     */
                    Route::prefix('payment_receipt')->group(function () {
                        Route::get('/', 'PaymentReceiptSubscriptionController@index')
                            ->name('tenant.subscription.payment_receipt.index');

                    });


                    // grados y secciones
                    Route::get('grade_section', 'SubscriptionController@indexGradeSection')->name('tenant.subscription.grade_section.index');

                    Route::prefix('grades')->group(function () {

                        Route::get('records', 'GradeController@records');
                        Route::get('columns', 'GradeController@columns');
                        Route::get('record/{id}', 'GradeController@record');
                        Route::post('', 'GradeController@store');
                        Route::delete('{id}', 'GradeController@destroy');

                    });

                    Route::prefix('sections')->group(function () {

                        Route::get('records', 'SectionController@records');
                        Route::get('columns', 'SectionController@columns');
                        Route::get('record/{id}', 'SectionController@record');
                        Route::post('', 'SectionController@store');
                        Route::delete('{id}', 'SectionController@destroy');

                    });
                    // grados y secciones


                    Route::post('CommonData', 'SubscriptionController@Tables');
                });
        });
}
