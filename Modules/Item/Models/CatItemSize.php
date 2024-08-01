<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class CatItemSize extends ModelTenant
{
    protected $table = 'cat_item_size';
    protected $perPage = 25;

    protected $fillable = [
        'name'
    ];

    public function item_sizes()
    {
        return $this->hasMany(ItemSize::class);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return CatItemSize
     */
    public function setName(string $name = ''): CatItemSize
    {
        $this->name = (string)$name;
        return $this;
    }

}
