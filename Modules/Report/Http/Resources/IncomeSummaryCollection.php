<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IncomeSummaryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {
            return [
                'id' => $row->id,
                'soap_type_id' => $row->soap_type_id,
                'soap_type_description' => $row->soap_type->description,
            ];
        });
    }
}
