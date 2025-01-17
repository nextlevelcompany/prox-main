<?php

namespace Modules\Voided\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentVoidedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => [
                'required'
            ],
            'voided_description' => [
                'required'
            ]
        ];
    }
}
