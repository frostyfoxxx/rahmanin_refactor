<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Services\AuthService;
use App\Services\ValidatorService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;
    private $validatorService;

    public function __construct(AuthService $authService, ValidatorService $validatorService)
    {
        $this->authService = $authService;
        $this->validatorService = $validatorService;
    }

    public function signUp(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request); // Метод глобальной валидации входящих данных

        if ($validated->fails()) {
            return response()->json([
                "code" => 422,
                "message" => "Validation error",
                "error" => $validated->errors()
            ], 422); // Метод, возврающий JSON-ошибки валидации
        }

        $users = $this->authService->checkCreateUser($request);
        if ($users) {
            return response()->json([
                'code' => 422,
                'message' => 'The user is already registered'
            ], 422);
        }

        $user = $this->authService->signUp($request);

        if ($user) {
            return response()->json([
                'code' => 201,
                'message' => 'Users has been created'
            ], 201);
        }
    }

    public function signIn(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return response()->json([
                "code" => 422,
                "message" => "Validation error",
                "error" => $validated->errors(),
            ], 422);
        }

        if (!$this->authService->checkCreateUser($request)) {
            return response()->json([
                'code' => 401,
                'message' => 'This user not register'
            ], 401);
        }

        $user = $this->authService->signIn();

        return response()->json([
            'code' => 200,
            'message' => 'Authentication successful',
            'role' => $user['role']
        ], 200)->withCookie($user['cookie']);
    }
}
