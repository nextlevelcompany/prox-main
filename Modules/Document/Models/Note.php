<?php

namespace Modules\Document\Models;

use Modules\Catalog\Models\NoteCreditType;
use Modules\Catalog\Models\NoteDebitType;
use App\Models\Tenant\ModelTenant;

class Note extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'note_type',
        'note_credit_type_id',
        'note_debit_type_id',
        'note_description',
        'affected_document_id',
        'data_affected_document',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document()
    {
        // return $this->hasOne(Document::class);
        return $this->belongsTo(Document::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affected_document()
    {
        return $this->belongsTo(Document::class, 'affected_document_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function note_credit_type()
    {
        return $this->belongsTo(NoteCreditType::class, 'note_credit_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function note_debit_type()
    {
        return $this->belongsTo(NoteDebitType::class, 'note_debit_type_id');
    }

    public function getDataAffectedDocumentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDataAffectedDocumentAttribute($value)
    {
        $this->attributes['data_affected_document'] = (is_null($value))?null:json_encode($value);
    }

    /**
     * @return bool
     */
    public function isDebit(){
        if($this->note_type === 'debit') return true;
        return false;
    }

    public function getDocument(){
        return Document::find($this->document_id);
    }


    /**
     *
     * Verificar si es nota tipo 13
     *
     * @return bool
     */
    public function isTypePaymentDateAdjustments()
    {
        return $this->note_credit_type_id === NoteCreditType::PAYMENT_DATE_ADJUSTMENTS_CODE;
    }

        
    /**
     * @return bool
     */
    public function isCredit()
    {
        return $this->note_type === 'credit';
    }

    
    /**
     * 
     * Obtener datos del documento afectado por la nota
     * Usado en pdf
     *
     * @return string
     */
    public function getAffectedDocumentNumberFull()
    {
        if($this->affected_document)
        {
            return $this->affected_document->series.'-'.str_pad($this->affected_document->number, 8, '0', STR_PAD_LEFT);
        }
        
        return $this->data_affected_document->series.'-'.str_pad($this->data_affected_document->number, 8, '0', STR_PAD_LEFT);
    }

    
    /**
     * 
     * Obtener descripcion del tipo de nota
     *
     * @return string
     */
    public function getNoteTypeDescription()
    {
        return $this->isCredit() ? $this->note_credit_type->description : $this->note_debit_type->description;
    }

}
