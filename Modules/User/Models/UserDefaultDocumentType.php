<?php

namespace Modules\User\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Catalog\Models\DocumentType;
use Modules\Establishment\Models\Series;

class UserDefaultDocumentType extends ModelTenant
{
    protected $fillable = [
        'user_id',
        'document_type_id',
        'series_id',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document_type():BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function series():BelongsTo
    {
        return $this->belongsTo(Series::class, 'series_id');
    }

    public function getDataMultipleDocumentType()
    {
        return [
            'user_id' => $this->user_id,
            'document_type_id' => $this->document_type_id,
            'series_id' => $this->series_id,
            'default_series' => [],
        ];
    }

}
