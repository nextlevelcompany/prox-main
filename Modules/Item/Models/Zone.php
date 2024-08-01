<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class Zone extends ModelTenant
{
    public $timestamps = false;
    protected $perPage = 25;
    protected $fillable = [
        'name'
    ];

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return Zone
     */
    public function setName(?string $name): Zone
    {
        $this->name = $name;
        return $this;
    }

}
