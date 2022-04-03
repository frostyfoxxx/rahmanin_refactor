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
    private $fields = ['phone_number', 'email', 'password'];

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

    /**
     * @OA\Post(
     *   path="/api/signup",
     *   summary="Регистрация",
     *   description="Регистрация по номеру телефона, почте и паролю",
     *   operationId="signUp",
     *   tags={"Регистрация и авторизация"},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Данные для регистрации пользователя",
     *     @OA\JsonContent(
     *       required={"phone_number", "email", "password"},
     *       @OA\Property(
     *         property="phone_number",
     *         type="string",
     *         example="8996501812"
     *       ),
     *       @OA\Property(
     *         property="email",
     *         type="string",
     *         format="email",
     *         example="ivanov3@mail.ru"
     *       ),
     *       @OA\Property(
     *         property="password",
     *         type="string",
     *         example="testpassword"
     *       )
     *     ),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Пользователь создан",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example="201"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="User has been created"
     *       ),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Этот пользователь уже зарегистрирован",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example="200"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The user is already registered"
     *       ),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Ошибки валидации",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example="422"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Validation error"
     *       ),
     *       @OA\Property(
     *         property="error",
     *         type="object",
     *         ref="#/components/schemas/Validation"
     *       )
     *     )
     *   )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function signUp(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request, $this->fields); // Метод глобальной валидации входящих данных

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

    /**
     * @OA\Post(
     *   path="/api/auth",
     *   summary="Авторизация",
     *   description="Авторизация по номеру телефона и паролю",
     *   operationId="signIn",
     *   tags={"Регистрация и авторизация"},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Данные для авторизации пользователя",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="phone_number",
     *         type="string",
     *         example="89965041812"
     *       ),
     *       @OA\Property(
     *         property="password",
     *         type="string",
     *         example="testpassword"
     *       )
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Успешная авторизация",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example=200
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Authentication successful"
     *       ),
     *       @OA\Property(
     *         property="role",
     *         type="string",
     *         example="student"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="401",
     *     description="Этот пользователь не зарегистрирован",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example=401
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="This user not register"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="422",
     *     description="Ошибки валидации",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example=422
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Validation error"
     *       ),
     *       @OA\Property(
     *         property="error",
     *         type="object",
     *         ref="#/components/schemas/Validation"
     *       )
     *     )
     *   )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function signIn(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request, $this->fields);

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
