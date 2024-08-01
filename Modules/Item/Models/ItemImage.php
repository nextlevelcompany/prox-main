<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemImage extends ModelTenant
{
    protected $fillable = [
        'item_id',
        'image',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
