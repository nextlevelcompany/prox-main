<?php

namespace Modules\Summary\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Document\Models\Document;

class SummaryDocument extends ModelTenant
{
    protected $with = ['document'];
    public $timestamps = false;

    protected $fillable = [
        'summary_id',
        'document_id',
        'description'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
