<?php

namespace App\ReturnData;

use Illuminate\Http\JsonResponse;

class ReturnData
{
    /**
     * Метод возвращающий JSON при ошибках валидации
     * @param $code
     * @param $message
     * @param $validatorObject
     * @return JsonResponse
     */
    public function returnValidationError($code, $message, $validatorObject): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'error' => $validatorObject->errors()
        ], $code);
    }

    /**
     * Метод, возвращающий стандартное JSON-представление
     * @param int $code - http-статус код ответа
     * @param string $message - сообщение
     * @return JsonResponse
     */
    public function returnDefaultData(int $code, string $message): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message
        ], $code);
    }

    /**
     * Метод, возвращающий ответ аутентифицированного пользователя
     * @param array $user - Массив с данными
     * @return JsonResponse
     */
    public function returnUserLogged(array $user) : JsonResponse
    {
        return response()->json([
            'code' => 200,
            'message' => 'Authentication successful',
            'role' => $user['role']
        ], 200)->withCookie($user['cookie']);
    }
}