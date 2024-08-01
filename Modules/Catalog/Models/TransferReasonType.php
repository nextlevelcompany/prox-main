<?php

namespace Modules\Catalog\Models;

class TransferReasonType extends ModelCatalog
{
    protected $table = "cat_transfer_reason_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'description',
        'discount_stock',
    ];

    protected $casts = [
        'active' => 'boolean'
    ];
}
