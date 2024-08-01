<?php

namespace Modules\Purchase\Models;

use Modules\User\Models\User;
use Modules\Company\Models\SoapType;
use Modules\Establishment\Models\Establishment;
use Modules\Company\Models\StateType;
use App\Models\Tenant\ModelTenant;

class PurchaseQuotation extends ModelTenant
{
    protected $fillable = [
        'id',
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'prefix',
        'date_of_issue',
        'time_of_issue',
        'suppliers',
        'filename',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getSuppliersAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setSuppliersAttribute($value)
    {
        $this->attributes['suppliers'] = (is_null($value))?null:json_encode($value);
    }


    public function getIdentifierAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseQuotationItem::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    

    /**
     * 
     * Obtener tablas relacionadas (with)
     * 
     * @param  Builder $query
     * @return Builder
     */
    public function scopeGetDataFromRelatedTables($query)
    {
        return $query->with([
            'items'
        ]);
    }

}
