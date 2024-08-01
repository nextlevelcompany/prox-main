<?php

namespace Modules\Sale\Models;

use Modules\Catalog\Models\AffectationIgvType;
use Modules\Catalog\Models\PriceType;
use Modules\Catalog\Models\SystemIscType;
use App\Models\Tenant\ModelTenant;
use App\Traits\AttributePerItems;

class SaleOpportunityItem extends ModelTenant
{
    use AttributePerItems;

    public $timestamps = false;

    protected $fillable = [
        'sale_opportunity_id',
        'item_id',
        'item',
        'quantity',
        'unit_value',
        'affectation_igv_type_id',
        'total_base_igv',
        'percentage_igv',
        'total_igv',
        'total_taxes',
        'price_type_id',
        'unit_price',
        'total_value',
        'total_charge',
        'total_discount',
        'total',
        'attributes',
        'charges',
        'discounts'
    ];

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    public function getAttributesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value))?null:json_encode($value);
    }

    public function getChargesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setChargesAttribute($value)
    {
        $this->attributes['charges'] = (is_null($value))?null:json_encode($value);
    }

    public function getDiscountsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDiscountsAttribute($value)
    {
        $this->attributes['discounts'] = (is_null($value))?null:json_encode($value);
    }

    public function affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
    }

    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }

    public function price_type()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }


    public function sale_opportunity()
    {
        return $this->belongsTo(SaleOpportunity::class, 'sale_opportunity_id');
    }

}
