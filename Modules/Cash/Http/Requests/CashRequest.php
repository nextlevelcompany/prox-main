<?php

namespace Modules\Cash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CashRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'beginning_balance' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }
}
