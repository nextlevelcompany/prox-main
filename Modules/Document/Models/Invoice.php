<?php

namespace Modules\Document\Models;

use Modules\Catalog\Models\OperationType;
use App\Models\Tenant\ModelTenant;

class Invoice extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'operation_type_id',
        'date_of_due',
    ];

    protected $casts = [
        'date_of_due' => 'date',
    ];

    public function document()
    {
        return $this->hasOne(Document::class);
    }

    public function operation_type()
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }
}
