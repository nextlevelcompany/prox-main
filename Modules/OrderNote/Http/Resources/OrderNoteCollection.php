<?php

namespace Modules\OrderNote\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\OrderNote\Models\OrderNote;

class OrderNoteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            /** @var OrderNote $row */
            return $row->getCollectionData();
            /* Movido al modelo */

        });
    }

}
