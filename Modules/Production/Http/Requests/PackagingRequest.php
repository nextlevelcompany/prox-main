<?php

namespace Modules\Production\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackagingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [

            'item_id' => [
                'required',
            ],
            'quantity' => [
                'required',
            ],
            'number_packages' => [
                'required',
            ],
            'establishment_id' => ['required'],
            'name' => ['required'],
            'date_start' => ['required'],
            'time_start' => ['required'],
            'date_end' => ['required'],
            'time_end' => ['required']
        ];
    }
}
