<?php

namespace Modules\Dispatch\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Person\Models\Person;


class DispatchAddress extends ModelTenant
{
    public $table = 'dispatch_addresses';
    public $timestamps = false;

    protected $fillable = [
        'person_id',
        'address',
        'location_id',
        'is_active'
    ];

    protected $casts = [
        'location_id' => 'array',
        'is_active' => 'boolean',
    ];

    
    public function person()
    {
        return $this->belongsTo(Person::class);
    }


    /**
     * 
     * Filtro para busquedas en listado
     *
     * @param  Builder $query
     * @param  Request $request
     * @return Builder
     */
    public function scopeWhereFilterRecords($query, $request)
    {
        return $query->with([
                        'person' => fn($person) => $person->selectColumnsRelationRecords()
                    ])
                    ->generalWhereLikeColumn($request->column, $request->value)
                    ->latest('id');
    }

    
    /**
     *
     * @return array
     */
    public function getResourceRecord()
    {
        return [
            'id' => $this->id,
            'person_id' => $this->person_id,
            'address' => $this->address,
            'location_id' => $this->location_id,
            'is_active' => $this->is_active,
        ];
    }

   
    /**
     *
     * @return array
     */
    public function getResourceCollection()
    {
        return [
            'id' => $this->id,
            'person_name' => $this->person->name,
            'person_number' => $this->person->number,
            'address' => $this->address,
            'location_name' => $this->location_id,
        ];
    }

    
}
