<?php

namespace Modules\Document\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class DocumentRegularizeShippingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {

            $btn_resend = $row->isAvailableResend();
            $text_tooltip = '';
            $affected_document = null;
            $btn_consult_cdr = $row->isAvailableConsultCdr();

            if ($row->group_id === '02') {
                if ($row->state_type_id === '01') {
                    $text_tooltip = 'Envíe mediante resúmen de boletas';
                }

                if ($row->state_type_id === '03') {
                    $text_tooltip = 'Consulte el ticket del resúmen de boletas';
                }
            }

            if ($row->regularize_shipping) {
                $message_regularize_shipping = "{$row->response_regularize_shipping->code} - {$row->response_regularize_shipping->description}";
            }

            return [
                'id' => $row->id,
                'soap_type_id' => $row->soap_type_id,
                'group_id' => $row->group_id,
                'soap_type_description' => $row->soap_type->description,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'number' => $row->number_full,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number,
                'total' => $row->total,
                'state_type_id' => $row->state_type_id,
                'state_type_description' => $row->state_type->description,
                'document_type_description' => $row->document_type->description,
                'document_type_id' => $row->document_type->id,
                'btn_resend' => $btn_resend,
                'affected_document' => $affected_document,
                'user_name' => ($row->user) ? $row->user->name : '',
                'user_email' => ($row->user) ? $row->user->email : '',
                'text_tooltip' => $text_tooltip,
                'message_regularize_shipping' => $message_regularize_shipping,
                'btn_consult_cdr' => $btn_consult_cdr,
            ];
        });
    }
}
