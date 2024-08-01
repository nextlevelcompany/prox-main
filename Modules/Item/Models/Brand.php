<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends ModelTenant
{
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

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
     * @return Brand
     */
    public function setName(?string $name): Brand
    {
        $this->name = $name;
        return $this;
    }


}
