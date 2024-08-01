<?php

namespace Modules\Dispatch\Models;

use Modules\Catalog\Models\IdentityDocumentType;
use App\Models\Tenant\ModelTenant;

class Dispatcher extends ModelTenant
{
    protected $fillable = [
        'identity_document_type_id',
        'number',
        'name',
        'address',
        'number_mtc',
        'is_default',
        'is_active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

}
