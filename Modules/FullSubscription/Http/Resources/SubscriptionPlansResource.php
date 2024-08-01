<?php

namespace Modules\FullSubscription\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlansResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->getCollectionData();

    }
}
