<?php

namespace App\Http\Requests;

class SignInRequest extends ApiRequest
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
            'password' => ['required', 'string', 'min:6']
        ];
    }
}
