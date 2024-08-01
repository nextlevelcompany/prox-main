<?php

namespace Modules\Company\Models;

use App\Models\Tenant\ModelTenant;

class ExchangeRate extends ModelTenant
{
    protected $fillable = [
        'date',
        'date_original',
        'purchase',
        'purchase_original',
        'sale',
        'sale_original',
    ];

    protected $casts = [
        'purchase' => 'float',
        'purchase_original' => 'float',
        'sale' => 'float',
        'sale_original' => 'float',
    ];
}
