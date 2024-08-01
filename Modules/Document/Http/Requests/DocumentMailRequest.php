<?php

namespace Modules\Document\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentMailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => [
                'required',
            ],
            'customer_email' => [
                'required',
            ],
        ];
    }
}
