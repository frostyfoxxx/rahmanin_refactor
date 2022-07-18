<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Roles;
use App\Models\User;
use App\Services\AuthService;
use App\Services\ValidatorService;
use http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signUp(SignUpRequest $request): JsonResponse
    {
        if (User::query()
            ->where('phone_number', '=', $request->input('phone_number'))
            ->orWhere('email', '=' . $request->input('email'))
            ->first()
        ) {
            throw new ApiException(422, 'This user already registered');
        }

        $user = User::create([
                'phone_number' => $request->input('phone_number'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'stuff' => false
            ]
        );
        $user->roles()->attach(Roles::where('slug', 'student')->first());
        $user->save();

        return response()->json([
            'code' => 201,
            'message' => "Users has been created"
        ])->setStatusCode(201);
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
     * @param SignInRequest $request
     * @return JsonResponse
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->all())) {
            throw new ApiException(422, 'Invalid login/password');
        }

        $user = Auth::user();
        $role = $user->roles[0]->slug;
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24 * 7); // 7 day;

        return response()->json([
            'code' => 200,
            'message' => 'Authentication successful',
            'role' => $role
        ])->withCookie($cookie)->setStatusCode(200);
    }

    /**
     *  TODO: Написать swagger-doc
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->detele();
        return response()->json([
            'code' => 200,
            'message' => 'Logout success'
        ], 200);
    }
}
