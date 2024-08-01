<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Item\Models\ItemLotsGroup;
use Modules\Item\Models\ItemLot;

class InventoryTransferItem extends ModelTenant
{
    protected $fillable = [
        'inventory_transfer_id',
        'item_lots_group_id',
        'item_lot_id',
    ];

    protected $casts = [
        'inventory_transfer_id' => 'int',
        'item_lots_group_id' => 'int',
        'item_lot_id' => 'int',
    ];

    public function inventories_transfer()
    {
        return $this->belongsTo(InventoryTransfer::class);
    }

    public function item_lot()
    {
        return $this->belongsTo(ItemLot::class);
    }

    public function item_lots_group()
    {
        return $this->belongsTo(ItemLotsGroup::class);
    }
}
