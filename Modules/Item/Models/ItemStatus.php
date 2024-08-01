<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;

class ItemStatus extends ModelTenant
{
    protected $table = 'item_status';
    protected $perPage = 25;

    protected $fillable = [
        'item_id',
        'cat_item_status_id',
        'active'
    ];

    protected $casts = [
        'item_id' => 'int',
        'cat_item_status_id' => 'int',
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
     * @return int
     */
    public function getItemId(): int
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     *
     * @return ItemStatus
     */
    public function setItemId(int $item_id): ItemStatus
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCatItemStatusId(): int
    {
        return $this->cat_item_status_id;
    }

    /**
     * @param int $cat_item_status_id
     *
     * @return ItemStatus
     */
    public function setCatItemStatusId(int $cat_item_status_id): ItemStatus
    {
        $this->cat_item_status_id = $cat_item_status_id;
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
     * @return ItemStatus
     */
    public function setActive(bool $active): ItemStatus
    {
        $this->active = $active;
        return $this;
    }


    /**
     * @return CatItemStatus
     */
    public function getCatItemStatus(): CatItemStatus
    {
        $e = CatItemStatus::find($this->cat_item_status_id);
        if (empty($e)) $e = new CatItemStatus();
        $this->cat_item_status = $e;
        $this->cat_item_status_id = $e->id;

        return $this->cat_item_status;
    }

    /**
     * @param CatItemStatus $cat_item_status
     *
     * @return ItemStatus
     */
    public function setCatItemStatus(CatItemStatus $cat_item_status): ItemStatus
    {
        $this->cat_item_status = $cat_item_status;
        $this->cat_item_status_id = $cat_item_status->id;
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
