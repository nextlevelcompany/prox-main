<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\Item;

class InventoryKardex extends ModelTenant
{
    protected $table = 'inventory_kardex';

    protected $fillable = [
        'date_of_issue',
        'item_id',
        'inventory_kardexable_id',
        'inventory_kardexable_type',
        'warehouse_id',
        'quantity',
    ];

    public function inventory_kardexable(): MorphTo
    {
        return $this->morphTo();
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
