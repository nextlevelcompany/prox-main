<?php

namespace Modules\OrderForm\Models;

use Modules\Catalog\Models\UnitType;
use Modules\User\Models\User;
use Modules\Company\Models\SoapType;
use Modules\Company\Models\StateType;
use Modules\Person\Models\Person;
use Modules\Establishment\Models\Establishment;
use App\Models\Tenant\ModelTenant;
use Modules\Dispatch\Models\Dispatch;
use Modules\Catalog\Models\{
    TransferReasonType,
    TransportModeType
};
use Modules\Dispatch\Models\{
    Dispatcher,
    Driver
};


class OrderForm extends ModelTenant
{
    protected $fillable = [
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
        'observations',
        'transport_mode_type_id',
        'transfer_reason_type_id',
        'transfer_reason_description',
        'date_of_shipping',
        'transshipment_indicator',
        'port_code',
        'unit_type_id',
        'total_weight',
        'packages_number',
        'container_number',
        'origin',
        'delivery',

        'dispatcher_id',
        'driver_id',
        'license_plates',
        'legends',
        'filename',
        'qr'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_shipping' => 'date',
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

    public function getOriginAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setOriginAttribute($value)
    {
        $this->attributes['origin'] = (is_null($value))?null:json_encode($value);
    }

    public function getDeliveryAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDeliveryAttribute($value)
    {
        $this->attributes['delivery'] = (is_null($value))?null:json_encode($value);
    }

    public function getLicensePlatesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLicensePlatesAttribute($value)
    {
        $this->attributes['license_plates'] = (is_null($value))?null:json_encode($value);
    }


    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
    }

    public function dispatch()
    {
        return $this->hasOne(Dispatch::class, 'reference_order_form_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function transport_mode_type()
    {
        return $this->belongsTo(TransportModeType::class, 'transport_mode_type_id');
    }

    public function transfer_reason_type()
    {
        return $this->belongsTo(TransferReasonType::class, 'transfer_reason_type_id');
    }

    public function items()
    {
        return $this->hasMany(OrderFormItem::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function dispatcher()
    {
        return $this->belongsTo(Dispatcher::class);
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
