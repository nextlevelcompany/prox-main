<?php

namespace Modules\Catalog\Models;

class CurrencyType extends ModelCatalog
{
    protected $table = "cat_currency_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'symbol',
        'description',
    ];

    public function scopeActives($query){
        return $query->where('active',1);
}
}
