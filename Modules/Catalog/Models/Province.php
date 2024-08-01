<?php

namespace Modules\Catalog\Models;

class Province extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'department_id',
        'description'
    ];

    static function idByDescription($description)
    {
        $province = Province::where('description', $description)->first();
        if ($province) {
            return $province->id;
        }
        return '1501';
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
