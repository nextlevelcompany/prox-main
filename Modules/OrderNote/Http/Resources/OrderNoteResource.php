<?php

namespace Modules\OrderNote\Http\Resources;

use Modules\OrderNote\Models\OrderNote;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderNoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order_note = OrderNote::getDataFromRelatedTables()->find($this->id);

        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'identifier' => $this->identifier,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'order_note' => $order_note
        ];
    }
}
