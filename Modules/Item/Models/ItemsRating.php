<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class ItemsRating extends ModelTenant
{
    protected $table = 'items_rating';

    protected $fillable = [
        'user_id',
        'item_id',
        'item_id',
        'value'
    ];

}
