<?php

namespace Modules\Retention\Models;

use Modules\Catalog\Models\CurrencyType;
use Modules\Catalog\Models\DocumentType;
use App\Models\Tenant\ModelTenant;

class RetentionDocument extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'retention_id',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'currency_type_id',
        'total_document',
        'payments',
        'exchange_rate',
        'date_of_retention',
        'total_retention',
        'total_to_pay',
        'total_payment',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_retention' => 'date'
    ];

    public function getPaymentsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPaymentsAttribute($value)
    {
        $this->attributes['payments'] = (is_null($value))?null:json_encode($value);
    }

    public function getExchangeRateAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setExchangeRateAttribute($value)
    {
        $this->attributes['exchange_rate'] = (is_null($value))?null:json_encode($value);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }
}
