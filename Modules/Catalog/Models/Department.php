<?php

namespace Modules\Catalog\Models;

class Department extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'description'
    ];

    static function idByDescription($description)
    {
        $department = Department::where('description', $description)->first();
        if ($department) {
            return $department->id;
        }
        return '15';
    }

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
}
