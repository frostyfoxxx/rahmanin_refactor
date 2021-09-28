<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DatabaseProvider extends ServiceProvider
{
    /*
        Метод позволяет проверить существование записи текущего пользователя.
        Входящие данные:
            Строка, содержащая в себе навзание HTTP-метода, где вызвана функция,
            Модель, в которой необходимо провести проверку,
            Сообщение об ошибке.
        Выходящие данные:
            Если httpMethod - GET:
                Булево значение результата проверки:
                    true - если не найдено,
                    false - если найдено.
                Ответ метода:
                    Если не найдено: функция с ошибкой,
                    Если найдено: Данные.
            Если httpMethod - POST:
                Булево значение результата проверки:
                    true - Если найдено,
                    false - Если не найдено.
                Ответ метода:
                    Если найдено: функция с ошибкой,
                    Если не найдено: пустота.

    */
    public static function checkExistData(String $httpMethod, $model, String $message)
    {
        $user = auth('sanctum')->user()->id;

        $user = $model::where('users_id', $user)->get();

        if ($httpMethod == 'GET') {
            if ($user->isEmpty()) {
                return [
                    'results' => true,
                    'response' => response()->json([
                        'errors' => [
                            'code' => 400,
                            'message' => $message
                        ]
                    ], 400)
                ];
            } else {
                return [
                    'results' => false,
                    'response' => $user
                ];
            }
        } else if ($httpMethod == 'POST') {
            if (!$user->isEmpty()) {
                return [
                    'results' => true,
                    'response' => response()->json([
                        'errors' => [
                            'code' => 400,
                            'message' => $message
                        ]
                    ], 400)
                ];
            } else {
                return [
                    'results' => false
                ];
            }
        } else {
            return [
                'results' => true,
                'response' => response()->json([
                    'errors' => [
                        'code' => 500,
                        'message' => 'Invalid parameter "httpMethod". The parameter must be passed in capital letters. For example: POST'
                    ]
                ], 500)
            ];
        }
    }

    public static function getData($data, $resource)
    {
        return response()->json([
            'data' => [
                'code' => 200,
                'message' => 'The requested data was found.',
                'content' => $resource::collection($data)
            ]
        ], 200);
    }
}
