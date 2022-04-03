<?php

namespace App\ReturnData;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentReturnData
{
    /**
     * Метод, возвращающий JSON-ответ без данных
     * @param string $message - Сообщение
     * @return JsonResponse
     */
    public function returnWithoutData(string $message) : JsonResponse
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
        ], 200);
    }

    /**
     * Метод, возвращающий JSON-ответ с данными
     * @param string $message - Сообщение
     * @param $data - коллекция данных
     * @return JsonResponse
     */
    public function returnData(string $message, JsonResource $data) : JsonResponse
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    /**
     * Метод, возвращающий JSON-ответ когда данные созданы
     * @param string $message - сообщение
     * @return JsonResponse
     */
    public function returnCreateData(string $message) : JsonResponse
    {
        return response()->json([
            'code' => 201,
            'message' => $message
        ], 201);
    }

    /**
     * Метод в
     * @param string $message
     * @param JsonResource $data
     * @param array $custom
     * @return JsonResponse
     */
    public function returnDataWithCustomField(string $message, JsonResource $data, array $custom) : JsonResponse
    {
        $json = [
            'code' => 200,
            'message' => $message,
            'data' => $data
        ];

        foreach ($custom as $field => $value) {
            $json[$field] = $value;
        }

        return response()->json($json, 200);
    }
}
