<?php

namespace App\Models\Tenant;

class FormatTemplate extends ModelTenant
{
    protected $fillable = [
    	'id',
    	'formats',
        'urls',
        'is_custom_ticket'
    ];

    public function getUrlAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['urls'] = (is_null($value))?null:json_encode($value);
    }

    public function getCollectionData()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->formats,
            'urls' => json_decode($this->urls),
        ];
        return $data;
    }
}
