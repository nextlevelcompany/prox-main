<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Builder;

class Promotion extends ModelTenant
{
    protected $fillable = [
        'type',
        'description',
        'name',
        'status',
        'image',
        'item_id',
        'apply_restaurant'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', 1);
        });
    }


}
