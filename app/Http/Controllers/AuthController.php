<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

define("TOKEN_LIFETIME", env('TOKEN_LIFETIME', 10080));

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/signup",
     *   summary="Регистрация",
     *   description="Регистрация по номеру телефона, почте и паролю",
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
        $userModel = new User();
        $userModel->createUser($request);
        return response()->json([
            'code' => Response::HTTP_CREATED,
            'message' => "Users has been created"
        ])->setStatusCode(Response::HTTP_CREATED);
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
            throw new ApiException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Invalid login/password'
            );
        }

        /** @var User $user */
        $user = Auth::user();
        $role = $user->roles[0]->slug;
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, TOKEN_LIFETIME);

        return response()->json([
            'code' => Response::HTTP_OK,
            'message' => 'Authentication successful',
            'role' => $role
        ])->withCookie($cookie)->setStatusCode(Response::HTTP_OK);
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
            'code' => Response::HTTP_OK,
            'message' => 'Logout success'
        ], Response::HTTP_OK);
    }
}
