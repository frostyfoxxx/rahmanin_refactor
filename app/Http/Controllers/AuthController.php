<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\ReturnData\AuthReturnData;
use App\ReturnData\ValidatorErrorReturnData;
use App\Services\AuthService;
use App\Services\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService, $validatorService, $validatorErrorReturnData, $authReturnData;


    public function __construct(
        AuthService $authService,
        ValidatorService $validatorService,
        ValidatorErrorReturnData $validatorErrorReturnData,
        AuthReturnData $authReturnData
    ) {
        $this->authService = $authService;
        $this->validatorService = $validatorService;
        $this->validatorErrorReturnData = $validatorErrorReturnData;
        $this->authReturnData = $authReturnData;
    }

    public function signUp(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request); // Метод глобальной валидации входящих данных

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated); // Метод, возврающий JSON-ошибки валидации
        }

        if ($this->authService->checkCreateUser($request)) {
            return $this->authReturnData->returnUserAlreadyRegistered();
        }

        $user = $this->authService->signUp($request);

        if ($user) {
            return $this->authReturnData->returnUserCreated();
        }
    }

    public function signIn(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated);
        }

        if (!$this->authService->checkCreateUser($request)) {
            return $this->authReturnData->returnUserNotRegister();
        }

        $user = $this->authService->signIn();

        return $this->authReturnData->returnUserLogged($user);
    }
}
