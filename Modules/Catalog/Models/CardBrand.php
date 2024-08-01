<?php

namespace Modules\Catalog\Models;

use App\Models\Tenant\ModelTenant;

class CardBrand extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'description',
        'id',
    ];
}
