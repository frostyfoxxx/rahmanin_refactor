<?php

namespace App\ReturnData;

class ValidatorErrorReturnData
{
    /**
     * Метод возвращающий JSON при ошибках валидации
     * @param  $validatorObject
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnData($validatorObject) {
        return response()->json([
            "code" => 422,
            "message" => "Validation error",
            "error" => $validatorObject->errors()
        ], 422);
    }
}
