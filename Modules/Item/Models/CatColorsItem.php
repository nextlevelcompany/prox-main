<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class CatColorsItem extends ModelTenant
{
    protected $perPage = 25;

    protected $fillable = [
        'name'
    ];

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
     * @return CatColorsItem
     */
    public function setName(string $name): CatColorsItem
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }


}
