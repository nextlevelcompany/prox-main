<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class ItemUnitsPerPackage extends ModelTenant
{
    protected $table = 'item_units_per_package';
    protected $perPage = 25;

    protected $fillable = [
        'item_id',
        'cat_item_units_per_package_id',
        'active'
    ];

    protected $casts = [
        'item_id' => 'int',
        'cat_item_units_per_package_id' => 'int',
        'active' => 'bool'
    ];

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        $e = Item::find($this->item_id);
        if (empty($e)) $e = new Item();
        $this->item = $e;
        $this->item_id = $e->id;
        return $this->item;
    }

    /**
     * @param Item $item
     *
     * @return $this
     */
    public function setItem(Item $item)
    {
        $this->item = $item;
        $this->item_id = $item->id;
        return $this;
    }

    /**
     * @return CatItemUnitsPerPackage
     */
    public function getCatItemUnitsPerPackage(): CatItemUnitsPerPackage
    {

        $e = CatItemUnitsPerPackage::find($this->cat_item_units_per_package_id);
        if (empty($e)) $e = new CatItemUnitsPerPackage();
        $this->cat_item_units_per_package = $e;
        $this->cat_item_units_per_package_id = $e->id;

        return $this->cat_item_units_per_package;
    }

    /**
     * @param CatItemUnitsPerPackage $cat_item_units_per_package
     *
     * @return ItemUnitsPerPackage
     */
    public function setCatItemUnitsPerPackage(CatItemUnitsPerPackage $cat_item_units_per_package): ItemUnitsPerPackage
    {
        $this->cat_item_units_per_package = $cat_item_units_per_package;
        $this->cat_item_units_per_package_id = $cat_item_units_per_package->id;
        return $this;
    }

    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     *
     * @return ItemUnitsPerPackage
     */
    public function setItemId(int $item_id): ItemUnitsPerPackage
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCatItemUnitsPerPackageId(): int
    {
        return $this->cat_item_units_per_package_id;
    }

    /**
     * @param int $cat_item_units_per_package_id
     *
     * @return ItemUnitsPerPackage
     */
    public function setCatItemUnitsPerPackageId(int $cat_item_units_per_package_id): ItemUnitsPerPackage
    {
        $this->cat_item_units_per_package_id = $cat_item_units_per_package_id;
        return $this;
    }

    /**
     * @return bool|true
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool|true $active
     *
     * @return ItemUnitsPerPackage
     */
    public function setActive(bool $active): ItemUnitsPerPackage
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function item_movement_rel_extra()
    {
        return $this->hasMany(ItemMovementRelExtra::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $item_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByItem($query, $item_id)
    {
        return $query->where('item_id', $item_id);
    }
}
