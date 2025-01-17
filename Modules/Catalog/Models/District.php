<?php

namespace Modules\Catalog\Models;

class District extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'province_id',
        'description'
    ];

    static function idByDescription($description, $province_id)
    {
        $district = District::where('description', $description)
                            ->where('province_id', $province_id)
                            ->first();
        if ($district) {
            return $district->id;
        }
        return '150101';
    }

    public function province()
    {
        return $this->belongsTo(Province::class)->with('department');
    }

}
