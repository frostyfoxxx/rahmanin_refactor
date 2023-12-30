<?php

namespace App\Http\Requests;

use App\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Базовый класс апи-реквестов. Позволяет наследуемым классам производить автоматическую валидацию по заданным правилам
 * Если в наследуемом классе не проходит валидация, вызывается ApiException, генерирующий ошибку валидации
 */
class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): ApiException
    {
        throw new ApiException(422, 'Validation Error', $validator->errors());
    }

}
