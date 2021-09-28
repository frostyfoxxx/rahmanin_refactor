<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Validated;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function signUp(Request $request)
    {
        $validated = Validated::globalValidation($request->all()); // Метод глобальной валидации входящих данных


        if ($validated->fails()) {
            return Validated::errorResponse($validated); // Метод, возврающий JSON-ошибки валидации
        }

        $users = User::checkCreateUser($request);
        if ($users) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'The user is already registered'
                ]
            ], 422);
        }

        $user = User::createUser($request);

        if ($user) {
            return response()->json([
                'data' => [
                    'code' => 201,
                    'message' => 'Users has been created'
                ]
            ], 201);
        }
    }

    public function signIn(Request $request)
    {
        $validated = Validated::globalValidation($request->all());

        if ($validated->fails()) {
            return Validated::errorResponse($validated);
        }

        if (!User::checkCreateUser($request)) {
            return response()->json([
                'errors' => [
                    'code' => 401,
                    'message' => 'This user not register'
                ]
            ], 401);
        }

        $user = User::loggedUser();

        return response()->json([
            'data' => [
                'code' => 200,
                'message' => 'Authentication successful',
                'role' => $user['role']
            ]
        ], 200)->withCookie($user['cookie']);
    }
}
