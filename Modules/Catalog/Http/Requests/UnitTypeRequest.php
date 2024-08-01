<?php

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'id' => [
                'required',
                Rule::unique('tenant.cat_unit_types')->ignore($id),
            ],
            'description' => [
                'required',
            ],
            'active' => [
                'required',
            ],
        ];
    }
}
