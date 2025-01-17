<?php

namespace Modules\SaleNote\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleNoteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => [
                'required',
            ],
            'exchange_rate_sale' => [
                'required',
                'numeric'
            ],
            'currency_type_id' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
            'series_id' => [
                'required',
            ],
        ];
    }
}
