<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Expense\Models\ExpensePayment;
use Modules\Cash\Models\Cash;
use Exception;

class CashServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->expense_payment();
    }

    private function expense_payment()
    {
        ExpensePayment::created(function ($expense_payment) {
            if ($expense_payment->expense_method_type_id === 1) {
                $cash = self::getCash();
                if (!$cash) {
                    throw new Exception("Para el método de gasto usado, primero debe aperturar caja chica");
                }
                $cash->cash_documents()->create(['expense_payment_id' => $expense_payment->id]);
            }
        });
    }


    private static function getCash()
    {
        return Cash::where([['user_id', auth()->user()->id], ['state', true]])->first();
    }
}
