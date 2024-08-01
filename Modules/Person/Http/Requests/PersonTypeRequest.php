<?php

namespace Modules\Person\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonTypeRequest extends FormRequest
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
            ]
        ];
    }
}
