<?php

namespace Modules\Voided\Models;

use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\SoapType;
use Modules\Company\Models\StateType;
use Modules\User\Models\User;

class Voided extends ModelTenant
{
    protected $table = 'voided';

    protected $fillable = [
        'user_id',
        'external_id',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'date_of_issue',
        'date_of_reference',
        'identifier',
        'filename',
        'ticket',
        'has_ticket',
        'has_cdr',
        'soap_shipping_response',

        'send_to_pse',
        'response_signature_pse',
        'response_send_cdr_pse',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_reference' => 'date',
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

    public function documents(): HasMany
    {
        return $this->hasMany(VoidedDocument::class);
    }

    /**
     * @return string
     */
    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'voided', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'voided', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'voided', 'type' => 'cdr', 'external_id' => $this->external_id]);
    }

    public function getSoapShippingResponseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setSoapShippingResponseAttribute($value)
    {
        $this->attributes['soap_shipping_response'] = (is_null($value)) ? null : json_encode($value);
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
            return $facturalo->loadDocument($model->id, 'voided');
        });
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
        return 'ANUL';
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
        $send_to_pse = false;

        // validar si los documentos informados en la RA fueron enviados a pse
        $voided_documents = $this->documents;
        $filter_quantity_documents = $voided_documents->where('document.send_to_pse', true)->count();

        if ($voided_documents->count() === $filter_quantity_documents) {
            $send_to_pse = true;
        } else {
            $sendDocumentPse->throwException('Documento a anular no fue enviado al PSE.');
        }

        return $send_to_pse;
    }


}
