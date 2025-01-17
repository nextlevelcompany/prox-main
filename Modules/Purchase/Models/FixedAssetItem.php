<?php

namespace Modules\Purchase\Models;

use Modules\Catalog\Models\AffectationIgvType;
use Modules\Catalog\Models\CurrencyType;
use Modules\Catalog\Models\UnitType;
use Modules\Item\Models\ItemType;
use App\Models\Tenant\ModelTenant;
use App\Traits\AttributePerItems;

class FixedAssetItem extends ModelTenant
{
    use AttributePerItems;

    protected $fillable = [
        'name',
        'description',
        'item_type_id',
        'internal_id',
        'unit_type_id',
        'currency_type_id',
        'purchase_unit_price',
        'purchase_affectation_igv_type_id',
    ];

    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function fixed_asset_purchase_item()
    {
        return $this->hasMany(FixedAssetPurchaseItem::class);
    }

    public function purchase_affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'purchase_affectation_igv_type_id');
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $this->scopeWhereWarehouse($query) : null;
    }


}
