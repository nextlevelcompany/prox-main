<?php

namespace Modules\Catalog\Models;

class AttributeType extends ModelCatalog
{
    protected $table = "cat_attribute_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'description',
    ];
}
