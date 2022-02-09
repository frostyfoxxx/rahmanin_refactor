<?php

namespace App\ReturnData;

use Illuminate\Http\JsonResponse;

class AuthReturnData
{
    /**
     * Метод, возвращающий ответ, если пользователь уже зарегистрирован
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnUserAlreadyRegistered() : JsonResponse
    {
        return response()->json([
            'code' => 422,
            'message' => 'The user is already registered'
        ], 422);
    }

    /**
     * Метод, возвращающий ответ при регистрации пользователя
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnUserCreated() : JsonResponse
    {
        return response()->json([
            'code' => 201,
            'message' => 'Users has been created'
        ], 201);
    }

    public function returnUserNotRegister() : JsonResponse
    {
        return response()->json([
            'code' => 401,
            'message' => 'This user not register'
        ], 401);
    }

    /**
     * Метод, возвращающий ответ залогиненного пользователя
     * @param array $user - Массив с данными
     * @return \Illuminate\Http\JsonResponse
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
