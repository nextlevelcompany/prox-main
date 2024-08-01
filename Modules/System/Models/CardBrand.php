<?php

namespace Modules\System\Models;

use App\Models\ModelSystem;

class CardBrand extends ModelSystem
{
    public $timestamps = false;

    protected $fillable = [
        'description',
    ];
}
