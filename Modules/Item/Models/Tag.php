<?php

namespace Modules\Item\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Tenant\ModelTenant;

class Tag extends ModelTenant
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', 1);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'status'];
}
