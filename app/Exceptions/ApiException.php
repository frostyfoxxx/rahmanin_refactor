<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use OpenApi\Annotations as OA;

/**
 * Класс-эксепшн для апи. Формирует удобный вывод всех возможных сценариев ошибок
 * @OA\Schema(
 *   schema="Validation",
 *   type="object",
 *   @OA\Property(
 *     property="field1",
 *     type="array",
 *     @OA\Items(
 *       example="The field1 field is required"
 *     )
 *   ),
 *   @OA\Property(
 *     property="field2",
 *     type="array",
 *     @OA\Items(
 *       example="The field2 must be a number."
 *     )
 *   )
 *  )
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
