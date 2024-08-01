<?php

namespace Modules\Subscription\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $id = $this->input('id');

        return [

            'name' => [
                'required',
                Rule::unique('tenant.subscription_grade')->ignore($id),
            ]
        ];

    }
}
