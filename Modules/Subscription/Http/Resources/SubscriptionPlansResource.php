<?php

namespace Modules\Subscription\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlansResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->getCollectionData();

    }
}
