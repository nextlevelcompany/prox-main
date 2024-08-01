<?php

namespace Modules\System\Models;

use App\Models\ModelSystem;

class ClientPayment extends ModelSystem
{
    protected $fillable = [
        'client_id',
        'date_of_payment',
        'payment_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'payment',
        'state',
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
}
