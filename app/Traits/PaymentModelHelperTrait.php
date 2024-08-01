<?php

namespace App\Traits;

trait PaymentModelHelperTrait
{
    public function scopeWhereCashPaymentMethodType($query)
    {
        return $query->whereHas('payment_method_type', function ($q) {
            $q->filterCashPayments();
        });
    }

}
