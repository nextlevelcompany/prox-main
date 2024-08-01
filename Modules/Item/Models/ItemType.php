<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class ItemType extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;
}
