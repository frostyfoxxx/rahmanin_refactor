<?php

namespace App\ReturnData;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentReturnData
{
    /**
     * Метод, возвращающий JSON-ответ без данных
     * @param string $message - Сообщение
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnWithoutData(string $message)
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
        ], 200);
    }

    /**
     * Метод, возвращающий JSON-ответ с данными
     * @param string $message - Сообщение
     * @param $data - данные с модели
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnData(string $message, JsonResource $data)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnCreateData(string $message)
    {
        return response()->json([
            'code' => 201,
            'message' => $message
        ], 201);
    }
}
