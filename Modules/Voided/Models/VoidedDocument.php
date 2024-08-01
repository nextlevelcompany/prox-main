<?php

namespace Modules\Voided\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Document\Models\Document;

class VoidedDocument extends ModelTenant
{
    protected $with = ['document'];
    public $timestamps = false;

    protected $fillable = [
        'voided_id',
        'document_id',
        'description'
    ];

    public function document():BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
