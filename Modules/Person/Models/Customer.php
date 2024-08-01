<?php

namespace Modules\Person\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Catalog\Models\Country;
use Modules\Catalog\Models\Department;
use Modules\Catalog\Models\District;
use Modules\Catalog\Models\IdentityDocumentType;
use Modules\Catalog\Models\Province;

class Customer extends ModelTenant
{
    protected $fillable = [
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'address',
        'email',
        'telephone',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
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
