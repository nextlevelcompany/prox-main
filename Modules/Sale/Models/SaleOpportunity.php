<?php

namespace Modules\Sale\Models;

use Modules\Catalog\Models\CurrencyType;
use Modules\User\Models\User;
use Modules\Company\Models\SoapType;
use Modules\Company\Models\StateType;
use Modules\Person\Models\Person;
use Modules\Quotation\Models\Quotation;
use App\Models\Tenant\ModelTenant;
use Modules\Purchase\Models\PurchaseOrder;


class SaleOpportunity extends ModelTenant
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
        'customer_id',
        'customer',
        'currency_type_id',
        'exchange_rate_sale',
        'total_exportation',
        'total_free',
        'total_taxed',
        'total_unaffected',
        'total_exonerated',
        'total_igv',
        'total_taxes',
        'total_value',
        'total',
        'filename',
        'detail',
        'observation'

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

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
    }

    public function getNumberFullAttribute()
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

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }


    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function items()
    {
        return $this->hasMany(SaleOpportunityItem::class);
    }

    public function files()
    {
        return $this->hasMany(SaleOpportunityFile::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class);
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
            'files',
            'items' => function($items){
                return $items->with([
                    'affectation_igv_type'
                ]);
            }
        ]);
    }


}
