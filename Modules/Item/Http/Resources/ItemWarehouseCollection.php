<?php

namespace Modules\Item\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemWarehouseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
      return $this->collection->transform(function($row, $key) {
          return [
              'id' => $row->id,
              'item_id' => $row->item->id,
              'stock' => $row->stock,
              'warehouse' => $row->warehouse->description,
              //'items' => $row->item
          ];
      });
    }
}
