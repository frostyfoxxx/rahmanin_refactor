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
        switch ($httpMethod) {
            case 'GET':
                $user = $model::where('users_id', $user)->get();
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
                break;

            case 'POST':
                $user = $model::where('users_id', $user)->get();
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
                break;

            case 'PATCH':
            case 'DELETE':
                $user = $model::where('users_id', $user)->first();
                if (count((array)$user) == 0) {
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
            default:
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

    /* 
        Метод, преобразующий данные с базы в JSON-формат
        Входящие данные: Данные с базы и класс ресурса для преобразования 
    */
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
    
    /* 
        Метод, добавляющий данные в необходимую таблицу Базы Данных
        Входящие данные:
            $request - Массив с данными
            $model - Модель таблицы Базы Данных
    */
    public static function addOnTable(array $request, $model) {
        $user = auth('sanctum')->user()->id;
        $postArray = [];

        foreach ($request as $key => $value) {
            $postArray[$key] = $value;
        }
        $postArray['users_id'] = $user;

        $model::create($postArray);
    }

    /* 
        Метод, обновляющий данные в необходимой строке.
        Входящие данные:
            $request - Массив с данными
            $data - Объект с данными из Базы Данных
    */
    public static function patchOnTable(array $res, Object $data)
    {       
        foreach ($res as $key => $value) {
            $data->$key = $value;
        }

        $data->save();

    }
}
