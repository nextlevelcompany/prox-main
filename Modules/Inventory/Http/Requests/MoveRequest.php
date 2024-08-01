<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'warehouse_new_id' => [
                'required',
            ],
            'quantity_move' => [
                'required',
                'gt:0',
            ],
        ];
    }
}
