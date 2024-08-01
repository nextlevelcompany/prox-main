<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class ItemTag extends ModelTenant
{
    protected $table = 'item_tags';

    protected $with = ['tag'];

    protected $fillable = [
        'item_id',
        'tag_id',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
