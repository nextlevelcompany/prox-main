<?php

namespace Modules\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required'
            ],
            'email' => [
                'required'
            ],
            'password' => [
                'min:6',
                'confirmed',
                'nullable',
            ],
            'phone' => [
                'numeric',
                'nullable',
            ],

        ];
    }
}
