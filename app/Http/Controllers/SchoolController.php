<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\ReturnData\StudentReturnData;
use App\ReturnData\ValidatorErrorReturnData;
use App\Services\SchoolService;
use App\Services\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    private $validatorService, $schoolService, $schoolReturnData, $validatorErrorReturnData;
    private $fields = ['number_of_classes', 'number_of_certificate', 'year_of_ending', 'school_name'];

    public function __construct(
        ValidatorService $validatorService,
        SchoolService $schoolService,
        StudentReturnData $schoolReturnData,
        ValidatorErrorReturnData $validatorErrorReturnData
    )
    {
        $this->validatorService = $validatorService;
        $this->schoolService = $schoolService;
        $this->schoolReturnData = $schoolReturnData;
        $this->validatorErrorReturnData = $validatorErrorReturnData;
    }

    public function getSchool() : JsonResponse
    {
        if (!$this->schoolService->checkSchoolData()) {
            return $this->schoolReturnData->returnWithoutData('School Data not found');
        }

        $data = $this->schoolService->getSchoolData();
        $collection = SchoolResource::collection($data);

        return $this->schoolReturnData->returnData('School Data found', $collection);
    }

    public function createSchoolData(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request, $this->fields);

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated);
        }

        if ($this->schoolService->checkSchoolData()) {
            return $this->schoolReturnData->returnWithoutData('School Data already exists');
        }

        $this->schoolService->createSchoolData($request);

        return $this->schoolReturnData->returnCreateData('School data has been created.');
    }

    public function updateSchoolData(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request, $this->fields);

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated);
        }

        if (!$this->schoolService->checkSchoolData()) {
            return $this->schoolReturnData->returnWithoutData('School Data not found');
        }

        $this->schoolService->updateSchoolData($request);

        return $this->schoolReturnData->returnWithoutData('Schools data has been updated');
    }
}
