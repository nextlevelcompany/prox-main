<?php

namespace Modules\System\Models;

use App\Models\ModelSystem;

class PaymentMethodType extends ModelSystem
{
    public $timestamps = false;

    protected $fillable = [
        'description',
    ];
}
