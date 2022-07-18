<?php

namespace App\Http\Requests;

class SignInRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone_number' => ['required', 'numeric', 'digits:11'],
            'password' => ['required', 'string', 'min:6']
        ];
    }
}