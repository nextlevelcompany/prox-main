<?php

namespace Modules\Item\Models;

use Modules\Catalog\Models\UnitType;
use App\Models\Tenant\ModelTenant;

class ItemUnitType extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'description',
        'item_id',
        'unit_type_id',
        'quantity_unit',
        'price1',
        'price2',
        'price3',
        'price_default',
        'barcode'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }


    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @param int $decimal_units
     *
     * @return array
     */
    public function getCollectionData($decimal_units = 2)
    {

        return [
            'id' => $this->id,
            'description' => "{$this->description}",
            'item_id' => $this->item_id,
            'unit_type_id' => $this->unit_type_id,
            'quantity_unit' => number_format($this->quantity_unit, $decimal_units, '.', ''),
            'price1' => number_format($this->price1, $decimal_units, '.', ''),
            'price2' => number_format($this->price2, $decimal_units, '.', ''),
            'price3' => number_format($this->price3, $decimal_units, '.', ''),
            'price_default' => $this->price_default,
            'barcode' => $this->barcode,
        ];
    }

}
