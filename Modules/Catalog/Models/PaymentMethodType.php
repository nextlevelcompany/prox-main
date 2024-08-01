<?php

namespace Modules\Catalog\Models;

class PaymentMethodType extends ModelCatalog
{
    protected $table = "cat_payment_method_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'description',
    ];
}
