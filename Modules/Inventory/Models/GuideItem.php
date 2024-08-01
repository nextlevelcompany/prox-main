<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Item\Models\Item;

class GuideItem extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'guide_id',
        'item_id',
        'item_name',
        'quantity',
        'unit_cost',
        'total',
    ];

    protected $casts = [
        'quantity' => 'float',
        'unit_cost' => 'float',
        'total' => 'float',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }
}
