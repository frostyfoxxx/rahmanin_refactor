<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Providers\ValidatorProvider;
use App\Services\AuthService;
use App\Services\ValidatorService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;
    private $validatorService;

    public function __construct(AuthService $authService, ValidatorService $validatorService) {
        $this->authService = $authService;
        $this->validatorService = $validatorService;
    }

    public function signUp(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request); // Метод глобальной валидации входящих данных

        if ($validated->fails()) {
            return response()->json([
                "error" => [
                    "code" => 422,
                    "message" => "Validation error",
                    "error" => $validated->errors(),
                ]
            ], 422); // Метод, возврающий JSON-ошибки валидации
        }

        $users = $this->authService->checkCreateUser($request);
        if ($users) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'The user is already registered'
                ]
            ], 422);
        }

        $user = $this->authService->signUp($request);

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
        $validated = ValidatorProvider::globalValidation($request->all());

        if ($validated->fails()) {
            return ValidatorProvider::errorResponse($validated);
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
