<?php

namespace App\Http\Controllers;

use App\Http\Resources\PassportResource;
use App\ReturnData\StudentReturnData;
use App\ReturnData\ValidatorErrorReturnData;
use App\Services\PassportService;
use App\Services\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    private $validatorService, $passportService, $passportReturnData, $validatorErrorReturnData;

    public function __construct(
        PassportService $passportService,
        ValidatorService $validatorService,
        StudentReturnData $passportReturnData,
        ValidatorErrorReturnData $validatorErrorReturnData
    )
    {
        $this->passportService = $passportService;
        $this->validatorService = $validatorService;
        $this->passportReturnData = $passportReturnData;
        $this->validatorErrorReturnData = $validatorErrorReturnData;
    }

    public function getPassportData() : JsonResponse
    {
        if (!$this->passportService->checkPassportData()) {
            return $this->passportReturnData->returnWithoutData('Passport Data not found');
        }

       $data = $this->passportService->getPassportData();
       $collection = PassportResource::collection($data);
        return$this->passportReturnData->returnData('Passport Data found', $collection);
    }

    public function createPassportData(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated);
        }


        if ($this->passportService->checkPassportData()) {
            return $this->passportReturnData->returnWithoutData('Passport Data already exists');
        }

        $this->passportService->createPassportData($request);

        return $this->passportReturnData->returnCreateData('Passport data has been created.');
    }

    public function updatePassportData(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated);
        }

        $this->passportService->updatePassportData($request);

        return $this->passportReturnData->returnWithoutData('Passports data has been updated');
    }
}
