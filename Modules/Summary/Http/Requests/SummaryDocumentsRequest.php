<?php

namespace Modules\Summary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SummaryDocumentsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date_of_reference' => [
                'required'
            ]
        ];
    }
}
