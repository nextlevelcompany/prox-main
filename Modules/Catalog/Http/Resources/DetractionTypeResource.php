<?php

namespace Modules\Catalog\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetractionTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'active' => (bool) $this->active,
            'percentage' => $this->percentage,
            'operation_type_id' => $this->operation_type_id,
            'description' => $this->description,
        ];
    }
}
