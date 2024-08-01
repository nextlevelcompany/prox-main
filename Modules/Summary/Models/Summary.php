<?php

namespace Modules\Summary\Models;

use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Models\SummaryStatusType;
use Modules\Company\Models\SoapType;
use Modules\Company\Models\StateType;
use Modules\User\Models\User;

class Summary extends ModelTenant
{
    protected $fillable = [
        'user_id',
        'external_id',
        'soap_type_id',
        'state_type_id',
        'summary_status_type_id',
        'ubl_version',
        'date_of_issue',
        'date_of_reference',
        'identifier',
        'filename',
        'ticket',
        'has_ticket',
        'has_cdr',
        'soap_shipping_response',
        'unknown_error_status_response',
        'manually_regularized',
        'error_manually_regularized',
        'unique_filename',

        'send_to_pse',
        'response_signature_pse',
        'response_send_cdr_pse',

    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_reference' => 'date',
        'unknown_error_status_response' => 'boolean',
        'manually_regularized' => 'boolean',
        'send_to_pse' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type(): BelongsTo
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type(): BelongsTo
    {
        return $this->belongsTo(StateType::class);
    }

    public function summary_status_type(): BelongsTo
    {
        return $this->belongsTo(SummaryStatusType::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(SummaryDocument::class);
    }

    /**
     * @return string
     */
    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'summary', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'summary', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'summary', 'type' => 'cdr', 'external_id' => $this->external_id]);
    }

    public function getSoapShippingResponseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setSoapShippingResponseAttribute($value)
    {
        $this->attributes['soap_shipping_response'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getErrorManuallyRegularizedAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setErrorManuallyRegularizedAttribute($value)
    {
        $this->attributes['error_manually_regularized'] = (is_null($value)) ? null : json_encode($value);
    }

    /**
     * Devuelve la clase Facturalo con los elementos cargados
     *
     * @return \App\CoreFacturalo\Facturalo
     */
    public function getFacturalo()
    {

        $model = $this;
        return DB::connection('tenant')->transaction(function () use ($model) {
            $facturalo = new Facturalo();
            return $facturalo->loadDocument($model->id, 'summary');
        });
    }


    /**
     *
     * Verificar si es un resumen para adicionar o modificar
     *
     * @return bool
     */
    public function isAddModifySummary()
    {
        return in_array($this->summary_status_type_id, ['1', '2']);
    }


    /**
     * Obtener tipo de documento válido para enviar el xml a firmar al pse
     *
     * Usado en:
     * App\CoreFacturalo\Services\Helpers\SendDocumentPse
     *
     * @return string
     */
    public function getDocumentTypeForPse()
    {
        return $this->isAddModifySummary() ? 'RESU' : 'REAN';
    }


    public function getResponseSendCdrPseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }


    public function setResponseSendCdrPseAttribute($value)
    {
        $this->attributes['response_send_cdr_pse'] = (is_null($value)) ? null : json_encode($value);
    }


    public function getResponseSignaturePseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }


    public function setResponseSignaturePseAttribute($value)
    {
        $this->attributes['response_signature_pse'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getSendToPse($sendDocumentPse)
    {
        $send_to_pse = true;
        return $send_to_pse;
    }

}
