<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonalsDataResource;
use App\ReturnData\StudentReturnData;
use App\ReturnData\ValidatorErrorReturnData;
use App\Services\PersonalService;
use App\Services\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonalDataController extends Controller
{
    private $personalService, $validatorService, $personalReturnData, $validatorErrorReturnData;

    public function __construct(
        ValidatorService         $validatorService,
        PersonalService          $personalService,
        StudentReturnData        $personalReturnData,
        ValidatorErrorReturnData $validatorErrorReturnData
    ) {
        $this->personalService = $personalService;
        $this->validatorService = $validatorService;
        $this->personalReturnData = $personalReturnData;
        $this->validatorErrorReturnData = $validatorErrorReturnData;
    }

    public function getPersonalData() : JsonResponse
    {
        /** Проверка на существование данных [false - нет, true - есть] */
        if (!$this->personalService->checkPersonalData()) {
            return $this->personalReturnData->returnWithoutData('Personal Data not found');
        }

        $data = $this->personalService->getPersonalData();
        $collection = PersonalsDataResource::collection($data);

        return $this->personalReturnData->returnData('Personal Data found', $collection);
    }

    public function postPersonalData(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated);
        }

        if ($this->personalService->checkPersonalData()) {
            return $this->personalReturnData->returnWithoutData('Personal Data already exists');
        }

        $this->personalService->addPersonalData($request);

        return $this->personalReturnData->returnCreateData('Personal data has been created');
    }

    public function patchPersonalData(Request $request)  : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated);
        }

        $this->personalService->patchPersonalData($request);

        return $this->personalReturnData->returnWithoutData('Personal data has been updated');
    }
}
