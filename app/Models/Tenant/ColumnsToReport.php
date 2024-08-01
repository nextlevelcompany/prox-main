<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

class ColumnsToReport extends ModelTenant
{
    protected $perPage = 25;

    protected $fillable = [
        'user_id',
        'report',
        'columns'
    ];

    protected $casts = [
        'user_id' => 'int'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getColumnsAttribute($value)
    {
        return (null === $value) ? null : (object)json_decode($value);
    }

    public function setColumnsAttribute($value)
    {
        $this->attributes['columns'] = (null === $value) ? null : json_encode($value);
    }
}
