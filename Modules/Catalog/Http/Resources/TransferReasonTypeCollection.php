<?php

namespace Modules\Catalog\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransferReasonTypeCollection extends ResourceCollection
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
                'active' => ($row->active)?'Si':'No',
                'discount_stock' => ($row->discount_stock)?'Si':'No',
                'description' => $row->description,
            ];
        });
    }
}
