<?php

namespace Modules\Document\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'series' => [
                'required',
            ],
        ];
    }
}
