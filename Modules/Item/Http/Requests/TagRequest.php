<?php

namespace Modules\Item\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [

            'description' => [
                'required',
            ],
             'name' => [
                'required',
            ]

        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'El campo nombre es obligatorio.',
        ];
    }
}
