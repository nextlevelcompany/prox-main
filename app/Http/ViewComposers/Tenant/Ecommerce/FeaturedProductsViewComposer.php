<?php

namespace App\Http\ViewComposers\Tenant\Ecommerce;

use Modules\Item\Models\Item;

class FeaturedProductsViewComposer
{
    public function compose($view)
    {
        $view->items = Item::query()
            ->where('apply_store', 1)
            ->get()
            ->transform(function ($row, $key) {
                return (object)[
                    'id' => $row->id,
                    'description' => $row->description,
                    'name' => $row->name,
                    'second_name' => $row->second_name,
                    'sale_unit_price' => $row->sale_unit_price,
                    'image' => $row->image,
                    'image_medium' => $row->image_medium,
                    'image_small' => $row->image_small,
                    'tags' => $row->tags->pluck('tag_id')->toArray()
                ];
            });
    }
}
