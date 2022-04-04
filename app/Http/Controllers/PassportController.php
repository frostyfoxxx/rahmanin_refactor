<?php

namespace App\Http\Controllers;

use App\Exceptions\ReturnDataException;
use App\Exceptions\ValidatorException;
use App\Http\Resources\PassportResource;
use App\ReturnData\ReturnData;
use App\Services\PassportService;
use App\Services\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    private $validatorService, $passportService, $returnData;
    private array $fields = [
        'series',
        'number',
        'date_of_issue',
        'issued_by',
        'date_of_birth',
        'gender',
        'place_of_birth',
        'registration_address',
        'lack_of_citizenship'
    ];

    public function __construct(
        PassportService $passportService,
        ValidatorService $validatorService,
        ReturnData $returnData
    ) {
        $this->passportService = $passportService;
        $this->validatorService = $validatorService;
        $this->returnData = $returnData;
    }

    public function getPassportData(): JsonResponse
    {
        try {
            if (!$this->passportService->checkPassportData()) {
                throw new ReturnDataException('Passport Data not found', 300);
            }

            $data = $this->passportService->getPassportData();
            $collection = PassportResource::collection($data);
            return $this->returnData->returnData(200, 'Passport Data found', $collection);
        } catch (ReturnDataException $exception) {
            return $this->returnData->returnDefaultData($exception->getCode(), $exception->getMessage());
        }
    }

    /**
     * @throws ValidatorException
     */
    public function createPassportData(Request $request): JsonResponse
    {
        try {
            $validated = $this->validatorService->globalValidation($request, $this->fields);

            if ($validated->fails()) {
                throw new ValidatorException('Validation Error', 422, $validated);
            }

            if ($this->passportService->checkPassportData()) {
                throw new ReturnDataException('Passport Data already exists', 300);
            }

            $this->passportService->createPassportData($request);

            return $this->returnData->returnDefaultData(201, 'Passport data has been created');
        } catch (ValidatorException $exception) {
            return $this->returnData->returnValidationError(
                $exception->getCode(),
                $exception->getMessage(),
                $exception->getValidatorObject()
            );
        } catch (ReturnDataException $exc) {
            return $this->returnData->returnDefaultData($exc->getCode(), $exc->getMessage());
        }
    }

    public function updatePassportData(Request $request): JsonResponse
    {
        try {
            $validated = $this->validatorService->globalValidation($request, $this->fields);

            if ($validated->fails()) {
                throw new ValidatorException('Validation Error', 422, $validated);
            }

            $this->passportService->updatePassportData($request);

            return $this->returnData->returnDefaultData(200,'Passports data has been updated');
        } catch (ValidatorException $e) {
            return $this->returnData->returnValidationError($e->getCode(), $e->getMessage(), $e->getValidatorObject());
        }
    }
}
