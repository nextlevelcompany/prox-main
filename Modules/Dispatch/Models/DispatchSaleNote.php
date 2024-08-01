<?php

namespace Modules\Dispatch\Models;

use App\Models\Tenant\ModelTenant;
use Modules\SaleNote\Models\SaleNote;

class DispatchSaleNote extends ModelTenant
{
    public $timestamps = false;
    protected $fillable = [
        'sale_note_id',
        'date_dispatch',
        'time_dispatch',
        'person_pick',
        'reference',
        'person_dispatch',
        'status',

    ];

    protected $casts = [
        'status' => 'bool',
    ];

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class, 'sale_note_id');
    }
}
