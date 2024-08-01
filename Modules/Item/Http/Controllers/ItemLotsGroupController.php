<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Item\Models\ItemLotsGroup;

class ItemLotsGroupController extends Controller
{
    public function getAvailableItemLotsGroup($item_id)
    {
        return ItemLotsGroup::query()
            ->where('item_id', $item_id)
            ->get()
            ->transform(function ($row) {
                return $row->getRowResourceSale();
            });
    }
}
