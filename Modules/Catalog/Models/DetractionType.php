<?php

namespace Modules\Catalog\Models;

class DetractionType extends ModelCatalog
{
    protected $table = "cat_detraction_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'percentage',
        'operation_type_id',
        'description',
    ];

}
