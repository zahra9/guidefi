<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Setting extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'card_no' => 'required|numeric|max:9999999999999999',
            'cvv'    => 'required|numeric|max:999',
            'expire_month' => 'required',
            'expire_year'    => 'required'

        ];

    }
}
