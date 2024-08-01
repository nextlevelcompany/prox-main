<?php

namespace App\Models\Tenant;

class GeneralPaymentCondition extends ModelTenant
{
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
    ];
}
