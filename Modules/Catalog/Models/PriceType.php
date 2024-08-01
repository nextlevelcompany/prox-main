<?php

namespace Modules\Catalog\Models;

use Modules\TechnicalService\Models\TechnicalServiceItem;

class PriceType extends ModelCatalog
{
    protected $table = "cat_price_types";
    public $incrementing = false;

    public  function technical_service_item()
    {
        return $this->hasMany(TechnicalServiceItem::class, 'price_type_id');
    }
}
