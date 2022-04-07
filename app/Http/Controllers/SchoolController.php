<?php

namespace App\Http\Controllers;

use App\Exceptions\ReturnDataException;
use App\Exceptions\ValidatorException;
use App\Http\Resources\SchoolResource;
use App\ReturnData\ReturnData;
use App\ReturnData\ValidatorErrorReturnData;
use App\Services\SchoolService;
use App\Services\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    private $validatorService, $schoolService, $schoolReturnData;
    private array $fields = ['number_of_classes', 'number_of_certificate', 'year_of_ending', 'school_name'];

    public function __construct(
        ValidatorService $validatorService,
        SchoolService $schoolService,
        ReturnData $schoolReturnData
    ) {
        $this->validatorService = $validatorService;
        $this->schoolService = $schoolService;
        $this->schoolReturnData = $schoolReturnData;
    }

    public function getSchool(): JsonResponse
    {
        try {
            if (!$this->schoolService->checkSchoolData()) {
                throw new ReturnDataException('School Data not found', 300);
            }
            $data = $this->schoolService->getSchoolData();
            $collection = SchoolResource::collection($data);

            return $this->schoolReturnData->returnData(200, 'School Data found', $collection);
        } catch (ReturnDataException $exc) {
            return $this->schoolReturnData->returnDefaultData($exc->getCode(), $exc->getMessage());
        }
    }

    public function createSchoolData(Request $request): JsonResponse
    {
        try {
            $validated = $this->validatorService->globalValidation($request, $this->fields);
            if ($validated->fails()) {
                throw new ValidatorException('Validation Error', 422, $validated);
            }

            if ($this->schoolService->checkSchoolData()) {
                throw new ReturnDataException('School Data already exists', 300);
            }

            $this->schoolService->createSchoolData($request);
            return $this->schoolReturnData->returnDefaultData(200, 'School data has been created.');
        } catch (ValidatorException $exception) {
            return $this->schoolReturnData->returnValidationError(
                $exception->getCode(),
                $exception->getMessage(),
                $exception->getValidatorObject()
            );
        } catch (ReturnDataException $exception) {
            return $this->schoolReturnData->returnDefaultData($exception->getCode(), $exception->getMessage());
        }
    }

    public function updateSchoolData(Request $request): JsonResponse
    {
        try {
            $validated = $this->validatorService->globalValidation($request, $this->fields);

            if ($validated->fails()) {
                throw new ValidatorException('Validation Error', 422, $validated);
            }

            if (!$this->schoolService->checkSchoolData()) {
                throw new ReturnDataException('School Data not found', 300);
            }

            $this->schoolService->updateSchoolData($request);

            return $this->schoolReturnData->returnDefaultData(200, 'Schools data has been updated');
        } catch (ValidatorException $exc) {
            return $this->schoolReturnData->returnValidationError(
                $exc->getCode(),
                $exc->getMessage(),
                $exc->getValidatorObject()
            );
        } catch (ReturnDataException $exc) {
            return $this->schoolReturnData->returnDefaultData($exc->getCode(), $exc->getMessage());
        }
    }
}
