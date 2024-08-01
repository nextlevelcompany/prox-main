<?php

use Illuminate\Support\Facades\Route;

use Modules\OrderNote\Http\Controllers\OrderNoteController;


$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);
if ($hostname) {
    Route::domain($hostname->fqdn)->group(function() {

        Route::middleware(['auth:api', 'locked.tenant'])->group(function() {

            Route::post('order-note/email', 'Api\OrderNoteController@email');

            Route::prefix('order-notes')->group(function () {
                Route::post('', [OrderNoteController::class, 'store']);
                Route::get('lists', 'Api\OrderNoteController@lists');
                Route::get('tables', 'Api\OrderNoteController@tables');
            });

        });

    });
}
