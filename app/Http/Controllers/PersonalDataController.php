<?php

namespace App\Http\Controllers;

use App\Exceptions\ReturnDataException;
use App\Exceptions\ValidatorException;
use App\Http\Resources\PersonalsDataResource;
use App\ReturnData\ReturnData;
use App\ReturnData\StudentReturnData;
use App\Services\PersonalService;
use App\Services\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonalDataController extends Controller
{
    private $personalService, $validatorService, $returnData;
    private $fields = ['phone', 'first_name', 'middle_name', 'last_name'];

    public function __construct(
        ValidatorService $validatorService,
        PersonalService $personalService,
        ReturnData $returnData
    ) {
        $this->personalService = $personalService;
        $this->validatorService = $validatorService;
        $this->returnData = $returnData;
    }

    /**
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
     * @throw ReturnDataException
     */
    public function getPersonalData(): JsonResponse
    {
        try {
            /** Проверка на существование данных [false - нет, true - есть] */
            if (!$this->personalService->checkPersonalData()) {
                throw new ReturnDataException('Personal Data not found', 300);
            }

            $data = $this->personalService->getPersonalData();
            $collection = PersonalsDataResource::collection($data);

            return $this->returnData->returnData(200, 'Personal Data found', $collection);
        } catch (ReturnDataException $dataException) {
            return $this->returnData->returnDefaultData($dataException->getCode(), $dataException->getMessage());
        }
    }

    /**
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
     * @param Request $request
     * @return JsonResponse
     */
    public function postPersonalData(Request $request): JsonResponse
    {
        try {
            $validated = $this->validatorService->globalValidation($request, $this->fields);

            if ($validated->fails()) {
                throw new ValidatorException('Validation Error', 422, $validated);
            }

            if ($this->personalService->checkPersonalData()) {
                throw new ReturnDataException('Personal Data already exists', 300);
            }

            $this->personalService->addPersonalData($request);

            return $this->returnData->returnDefaultData(201, 'Personal data has been created');
        } catch (ValidatorException $exc) {
            return $this->returnData->returnValidationError(
                $exc->getCode(),
                $exc->getMessage(),
                $exc->getValidatorObject()
            );
        } catch (ReturnDataException $exc) {
            return $this->returnData->returnDefaultData($exc->getCode(), $exc->getMessage());
        }
    }

    /**
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
     * @param Request $request
     * @return JsonResponse
     */
    public function patchPersonalData(Request $request): JsonResponse
    {
        try {
            $validated = $this->validatorService->globalValidation($request, $this->fields);

            if ($validated->fails()) {
                throw new ValidatorException('Validation Error', 422, $validated);
            }

            if (!$this->personalService->checkPersonalData()) {
                throw new ReturnDataException('Personal data has not been created yet', 300);
            }
            $this->personalService->patchPersonalData($request);

            return $this->returnData->returnDefaultData(200, 'Personal data has been updated');
        } catch (ValidatorException $exc) {
            return $this->returnData->returnValidationError(
                $exc->getCode(),
                $exc->getMessage(),
                $exc->getValidatorObject()
            );
        } catch (ReturnDataException $exc) {
            return $this->returnData->returnDefaultData($exc->getCode(), $exc->getMessage());
        }
    }
}
