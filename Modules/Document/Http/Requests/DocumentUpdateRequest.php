<?php

namespace Modules\Document\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentUpdateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric',
            'customer_id' => [
                'required',
            ],
            'establishment_id' => [
                'required',
            ],
            'series' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
            'exchange_rate_sale' => [
                'required',
                'numeric',
                'min:0.01'
            ],
        ];
    }
}
