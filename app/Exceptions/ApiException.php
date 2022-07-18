<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Класс-эксепшн для апи. Формирует удобный вывод всех возможных сценариев ошибок
 */
class ApiException extends HttpResponseException
{
    public function __construct($code = 422, $message = 'Validation Error', $errors = [])
    {
        $data = [
            'code' => $code,
            'message' => $message,
        ];
        if (count($errors) > 0) {
            $data['errors'] = $errors;
        }

        parent::__construct(response()->json($data)->setStatusCode($code));
    }

}
