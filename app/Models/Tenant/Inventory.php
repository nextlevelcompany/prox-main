<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\Item;

class Inventory extends ModelTenant
{
    protected $fillable = [
        'type',
        'description',
        'item_id',
        'warehouse_id',
        'warehouse_destination_id',
        'quantity',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function warehouse_destination(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function inventory_kardex(): MorphMany
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }
}
