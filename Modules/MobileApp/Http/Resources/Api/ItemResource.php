<?php

namespace Modules\MobileApp\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemResource
 *
 * @package App\Http\Resources\Tenant
 * @mixin JsonResource
 */
class ItemResource extends JsonResource
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
        return $this->getApiRowResource();
    }
}
