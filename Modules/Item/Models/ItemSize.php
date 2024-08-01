<?php

namespace Modules\Item\Models;

use App\Models\Tenant\CatItemSize;
use App\Models\Tenant\ModelTenant;

class ItemSize extends ModelTenant
{
    protected $table = 'item_size';
    protected $perPage = 25;

    protected $fillable = [
        'item_id',
        'cat_item_size_id',
        'active',
    ];

    protected $casts = [
        'item_id' => 'int',
        'cat_item_size_id' => 'int',
        'active' => 'bool'
    ];

    public function cat_item_size()
    {
        return $this->belongsTo(CatItemSize::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function item_movement_rel_extras()
    {
        return $this->hasMany(ItemMovementRelExtra::class);
    }


    /**
     * @return int|null
     */
    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    /**
     * @param int|null $item_id
     *
     * @return ItemSize
     */
    public function setItemId(?int $item_id): ItemSize
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCatItemSizeId(): ?int
    {
        return $this->cat_item_size_id;
    }

    /**
     * @param int|null $cat_item_size_id
     *
     * @return ItemSize
     */
    public function setCatItemSizeId(?int $cat_item_size_id): ItemSize
    {
        $this->cat_item_size_id = $cat_item_size_id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return ItemSize
     */
    public function setActive(bool $active): ItemSize
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
