<?php

namespace Modules\Pos\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Company\Models\SoapType;
use Modules\Document\Models\Document;

class Tip extends ModelTenant
{
    protected $fillable = [
        'soap_type_id',
        'date',
        'origin_date_of_issue',
        'origin_id',
        'origin_type',
        'worker_full_name',
        'total',
    ];

    protected $casts = [
        'origin_date_of_issue' => 'date',
    ];


    /**
     * @return MorphTo
     */
    public function origin()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function getDocumentTypeDescriptionAttribute()
    {
        return $this->origin_type === Document::class ? $this->origin->document_type->description ?? null : 'NOTA DE VENTA';
    }

    public function getDocumentNumberFullAttribute()
    {
        return optional($this->origin)->number_full;
    }

    /**
     *
     * Usado para mostrar data en vista y formatos pdf/excel
     *
     * @return array
     */
    public function getRowResource()
    {
        return [

            'soap_type_id' => $this->soap_type_id,
            'date' => $this->date,
            'origin_date_of_issue' => $this->origin_date_of_issue->format('Y-m-d'),
            'origin_id' => $this->origin_id,
            'worker_full_name' => $this->worker_full_name,
            'total' => $this->total,
            'document_type_description' => $this->document_type_description,
            'document_number_full' => $this->document_number_full,
            'state_type_description' => $this->origin->state_type->description ?? null,
        ];
    }

}
