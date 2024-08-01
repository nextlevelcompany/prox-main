<?php

namespace Modules\Establishment\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Establishment\Models\Series;
use Modules\Catalog\Models\DocumentType;

class SeriesConfiguration extends ModelTenant
{
    protected $fillable = [
        'series_id',
        'series',
        'number',
        'document_type_id',
    ];

    public function relationSeries()
    {
        return $this->belongsTo(Series::class,'series_id');
    }

    public function document_type() {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

}
