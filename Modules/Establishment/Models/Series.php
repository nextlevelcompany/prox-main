<?php

namespace Modules\Establishment\Models;

use Modules\Catalog\Models\DocumentType;
use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Document\Models\Document;

class Series extends ModelTenant
{
    protected $table = 'series';

    protected $fillable = [
        'establishment_id',
        'document_type_id',
        'number',
        'contingency',
    ];

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function document_type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    /**
     * @param $value
     */
    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = strtoupper($value);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'series', 'number');
    }

    public function series_configurations(): HasOne
    {
        return $this->hasOne(SeriesConfiguration::class);
    }


    /**
     * @param int $establishment_id
     *
     * @return Builder
     */
    public function scopeFilterEstablishment($query, $establishment_id = 0)
    {
        return $query->where('establishment_id', $establishment_id);
    }

    /**
     * @param int $document_type_id
     *
     * @return Builder
     */
    public function scopeFilterDocumentType($query, $document_type_id = 0)
    {
        return $query->where('document_type_id', $document_type_id);
    }

    /**
     * @param Builder $query
     * @param int $establishment_id
     *
     * @return Builder
     */
    public function scopeFilterSeries(Builder $query, $establishment_id = 0)
    {
        $query->where('establishment_id', $establishment_id);
        return $query;
    }

    /**
     * Devuelve un array de datos con estrcutura unificada para series
     *
     * @param int|null $document_id
     * @param int|null $series_id
     * @param string|null $userType
     *
     * @return array
     */
    public function getCollectionData(?int $document_id = 0, ?int $series_id = 0, ?string $userType = 'seller'): array
    {
        $document_id = (int)$document_id;
        $series_id = (int)$series_id;
        $disabled = false;
        if ($document_id == $this->document_type_id && $userType !== 'admin') {
            $disabled = !(($series_id == $this->id));
        }
        return [
            'id' => $this->id,
            'contingency' => (bool)$this->contingency,
            'document_type_id' => $this->document_type_id,
            'establishment_id' => $this->establishment_id,
            'number' => $this->number,
            'disabled' => $disabled,
        ];

    }


    /**
     *
     * Validar y determinar serie por defecto para el usuario
     *
     * @return bool
     */
    public function getIsDefaultAttribute()
    {
        $is_default = false;
        $user = auth()->user();
        $default_series_id = $user->series_id;
        $default_document_type_id = $user->document_id;

        if ($default_document_type_id === $this->document_type_id && $default_series_id === $this->id) {
            $is_default = true;
        }

        return $is_default;
    }


    /**
     *
     * Obtener datos para api (app)
     *
     * @return array
     */
    public function getApiRowResource()
    {
        return [
            'id' => $this->id,
            'document_type_id' => $this->document_type_id,
            'number' => $this->number,
            'is_default' => $this->is_default,
        ];
    }

    /**
     *
     * Filtrar series para documentos de venta, cpe y nv - modo pos app
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlySaleDocuments($query)
    {
        return $query->where('establishment_id', auth()->user()->establishment_id)
            ->whereIn('document_type_id', DocumentType::SALE_DOCUMENT_TYPES);
    }

    
    /**
     *
     * Filtrar series para documentos en app
     *
     * @param string $document_type_id
     * @return array
     */
    public static function filterDataByDocumentType($document_type_id)
    {
        return self::where('establishment_id', auth()->user()->establishment_id)
                    ->where('document_type_id', $document_type_id)
                    ->select([
                        'id',
                        'establishment_id',
                        'document_type_id',
                        'number'
                    ])
                    ->get();
    }

}
