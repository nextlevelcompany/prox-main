<?php

namespace Modules\SaleNote\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleNotePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'date_of_payment' => [
                'date',
                'required',
            ],
            'payment_method_type_id' => [
                'required',
            ],
            'payment_destination_id' => [
                'required',
            ],
            'payment' => [
                'required',
            ],
        ];
    }
}
