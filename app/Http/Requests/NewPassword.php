<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPassword extends FormRequest
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

            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'password_confirmation'   => 'required'

        ];

    }
    public function messages()
    {
        return [
            'password.regex' => 'Minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character',
            'password.same' => 'New password and confirm password must match.',
            'password_confirmation.required' => 'Confirm password field cannot be empty!!',
        ];
    }
}

