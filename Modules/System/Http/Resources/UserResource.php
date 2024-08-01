<?php

namespace Modules\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\System\Models\Configuration;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $configuration = Configuration::first();
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
            'enable_whatsapp' => (bool)$configuration->enable_whatsapp,
        ];
    }
}
