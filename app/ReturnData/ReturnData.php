<?php

namespace App\ReturnData;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnData
{
    /**
     * Метод возвращающий JSON при ошибках валидации
     * @param $code - Http-код ответа
     * @param $message - Сообщение ответа
     * @param $validatorObject - массив ошибок валидации
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
     * Метод, возвращающий JSON-представление с массивом данных
     * @param int $code - Http-код ответа
     * @param string $message - Сообщение ответа
     * @param JsonResource $data - JsonResource представление данных
     * @return JsonResponse
     */
    public function returnData(int $code, string $message, JsonResource $data): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
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