<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\PersonalDataRequest;
use App\Http\Resources\PersonalsDataResource;
use App\Models\PersonalsData;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PersonalDataController extends Controller
{
    /**
     * Метод получения персональных данных
     * @OA\Get(
     *   path="/api/user/personal",
     *   summary="Получение персональных данных",
     *   description="Получение персональных данных по куки-файлу",
     *   operationId="getPersonalData",
     *   tags={"Персональные данные"},
     *   @OA\RequestBody(
     *     required=false
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Персональные данные получены",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example="200"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Personal Data found"
     *       ),
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(
     *           ref="#/components/schemas/PersonalData"
     *         )
     *       ),
     *     ),
     *   ),
     *   @OA\Response(
     *     response=300,
     *     description="Персональные данные не найдены",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example=400
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Personal Data not found"
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
     *         example=422
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Validation error"
     *       ),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         ref="#/components/schemas/Validation"
     *       ),
     *     )
     *   ),
     * )
     * @return JsonResponse
     */
    public function getPersonalData(): JsonResponse
    {
        $foundedData = Auth::user()->personalData ? Auth::user()->personalData->all() : [];
        return response()->json([
            'code' => Response::HTTP_CREATED,
            'message' => 'Personal Data founded',
            'data' => PersonalsDataResource::collection($foundedData)
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Метод создания персональных данных
     * @OA\Post(
     *   path="/api/user/personal",
     *   summary="Добавление персональных данных",
     *   description="Добавление персональных данных",
     *   operationId="postPersonalData",
     *   tags={"Персональные данные"},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Персональные данные",
     *     @OA\JsonContent(
     *       required={"first_name", "middle_name", "last_name", "phone"},
     *       @OA\Property(
     *         property="first_name",
     *         type="string",
     *         example="Иван"
     *       ),
     *       @OA\Property(
     *         property="middle_name",
     *         type="string",
     *         example="Иванович"
     *       ),
     *       @OA\Property(
     *         property="last_name",
     *         type="string",
     *         example="Иванов"
     *       ),
     *       @OA\Property(
     *         property="phone",
     *         type="numeric",
     *         example=89005553535
     *       ),
     *     ),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Персональные данные созданы",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example="201"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Personal Data has been updated"
     *       )
     *     ),
     *   ),
     *   @OA\Response(
     *     response=300,
     *     description="Персональных данных не существуeт",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example=300
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Personal Data already exists"
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
     *         example=422
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Validation error"
     *       ),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         ref="#/components/schemas/Validation"
     *       ),
     *     )
     *   ),
     * )
     * @param PersonalDataRequest $request
     * @return JsonResponse
     */
    public function postPersonalData(PersonalDataRequest $request): JsonResponse
    {
        $pData = new PersonalsData();
        if (Auth::user()->personalData) {
            throw new ApiException(
                Response::HTTP_MULTIPLE_CHOICES,
                'Personal Data already exists'
            );
        }
        $pData->createPersonalData($request);

        return response()->json([
            'code' => Response::HTTP_CREATED,
            'message' => 'Personal Data has been created'
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Метод обновления персональных данных
     * TODO: Обновить swagger-doc
     * @OA\Patch(
     *   path="/api/user/personal",
     *   summary="Изменение персональных данных",
     *   description="Изменение персональных данных",
     *   operationId="postPersonalData",
     *   tags={"Персональные данные"},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Персональные данные",
     *     @OA\JsonContent(
     *       required={"first_name", "middle_name", "last_name", "phone"},
     *       @OA\Property(
     *         property="first_name",
     *         type="string",
     *         example="Иван"
     *       ),
     *       @OA\Property(
     *         property="middle_name",
     *         type="string",
     *         example="Иванович"
     *       ),
     *       @OA\Property(
     *         property="last_name",
     *         type="string",
     *         example="Иванов"
     *       ),
     *       @OA\Property(
     *         property="phone",
     *         type="numeric",
     *         example=89005553535
     *       ),
     *     ),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Персональные данные изменены",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example="200"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Personal Data has been created"
     *       )
     *     ),
     *   ),
     *   @OA\Response(
     *     response=300,
     *     description="Персональные данные уже существуют",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="code",
     *         type="int",
     *         example=300
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Personal data has not been created yet"
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
     *         example=422
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Validation error"
     *       ),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         ref="#/components/schemas/Validation"
     *       ),
     *     )
     *   ),
     * )
     * @param PersonalDataRequest $request
     * @return JsonResponse
     */
    public function patchPersonalData(PersonalDataRequest $request): JsonResponse
    {
        /** @var PersonalsData $personalData */
        $personalData = Auth::user()->personalData;
        if (!$personalData) {
            throw new ApiException(300, 'Personal data not found for updating');
        }

        $personalData->updatePersonalData($personalData, $request);

        return response()->json([
            'code' => Response::HTTP_OK,
            'message' => 'Personal data has been updated'
        ], Response::HTTP_OK);
    }
}
