<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use Carbon\Carbon;

/**
 * Class CatItemUnitBusiness
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @package App\Models
 */
class CatItemUnitBusiness extends ModelTenant
{
    protected $table = 'cat_item_unit_business';
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
     * @return $this
     */
    public function setName(string $name): CatItemUnitBusiness
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }
}
