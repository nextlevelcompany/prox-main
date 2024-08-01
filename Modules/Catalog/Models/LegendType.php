<?php

namespace Modules\Catalog\Models;

class LegendType extends ModelCatalog
{
    protected $table = "cat_legend_types";
    public $incrementing = false;

    public function scopeFilterLegendsForest($query)
    {
        return $query->whereActive()->whereIn('id', ['2001']);
        // ['2001', '2002'] si se añade servicios
    }

}
