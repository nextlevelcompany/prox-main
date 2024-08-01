<?php

namespace Modules\Person\Models;

use Modules\Catalog\Models\Department;
use Modules\Catalog\Models\District;
use Modules\Catalog\Models\Province;
use Modules\Catalog\Models\Country;
use App\Models\Tenant\ModelTenant;

class PersonAddress extends ModelTenant
{
    protected $table = 'person_addresses';
    public $timestamps = false;

    protected $fillable = [
        'person_id',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'address',
        'location_id',
        'phone',
        'email',
        'main',
    ];

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @return array
     */
    public function getCollectionData() {
        return [
            'id' => $this->id,
            'trade_name' => $this->trade_name,
            'country_id' => $this->country_id,
            'location_id' => !is_null($this->location_id)?$this->location_id:[],
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'main' => (bool)$this->main,

            'department_id' => $this->department_id,
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,

        ];
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function setLocationIdAttribute($value)
    {
        $this->attributes['department_id'] = (count($value) === 3)?$value[0]:null;
        $this->attributes['province_id'] = (count($value) === 3)?$value[1]:null;
        $this->attributes['district_id'] = (count($value) === 3)?$value[2]:null;
    }

    public function getLocationIdAttribute()
    {
        return [
            $this->department_id,
            $this->province_id,
            $this->district_id,
        ];
    }

    public function getAddressFullAttribute()
    {
        $address = trim($this->address);
        $address = ($address === '-' || $address === '')?'':$address.' ,';
        if ($address === '') {
            return '';
        }
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }
}
