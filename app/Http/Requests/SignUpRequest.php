<?php

namespace App\Http\Requests;

class SignUpRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'numeric', 'digits:11'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6']
        ];
    }
}
