<?php

namespace Modules\Catalog\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodTypeResource extends JsonResource
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
            'description' => $this->description,
        ];
    }
}
