<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'customer_email' => $this->customer->correo_electronico,
            'print_ticket' => url('')."/orders/print/{$this->external_id}/ticket",
            'print_a4' => url('')."/orders/print/{$this->external_id}/a4",
            'print_a5' => url('')."/orders/print/{$this->external_id}/a5",
            'customer_telephone' => $this->customer->telefono,
            'message_text' => "Su comprobante de pago electrónico {$this->number_full} ha sido generado correctamente, puede revisarlo en el siguiente enlace: ".url('')."/print/document/{$this->external_id}/a4"."",
            'address' => $this->customer->direccion,
            'number_document' => $this->number_document,
            'order_id' => str_pad($this->id, 6, "0", STR_PAD_LEFT),
            'customer' => $this->customer->apellidos_y_nombres_o_razon_social,
            'items' => $this->items,
            'total' => $this->total,
            'reference_payment' => strtoupper($this->reference_payment),
            'document_external_id' => $this->document_external_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'status_order_id' => $this->status_order_id
        ];
    }
}
