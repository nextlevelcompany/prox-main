<?php

namespace Modules\Sale\Models;

use Modules\Catalog\Models\CardBrand;
use Modules\Finance\Models\GlobalPayment;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\ModelTenant;

class ContractPayment extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'contract_id',
        'date_of_payment',
        'payment_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'change',
        'payment',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
    ];

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function card_brand()
    {
        return $this->belongsTo(CardBrand::class);
    }

    public function global_payment()
    {
        return $this->morphOne(GlobalPayment::class, 'payment');
    }

    public function associated_record_payment()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function scopeFilterRelationsPayments($query)
    {
        return $query->generalPaymentsWithOutRelations()
                    ->with([
                        'payment_method_type' => function($payment_method_type){
                            $payment_method_type->select('id', 'description');
                        },
                    ]);
    }

}
