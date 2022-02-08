<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppraisalResource;
use App\Models\Appraisal;
use App\ReturnData\StudentReturnData;
use App\ReturnData\ValidatorErrorReturnData;
use App\Services\AppraisalService;
use App\Services\ValidatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{
    private $appraisalService, $validatorService, $appraisalReturnData, $validatorErrorReturnData;
    private $fields = ['*.subject', '*.appraisal'];

    public function __construct(
        AppraisalService         $appraisalService,
        ValidatorService         $validatorService,
        StudentReturnData        $appraisalReturnData,
        ValidatorErrorReturnData $validatorErrorReturnData
    )
    {
        $this->appraisalService = $appraisalService;
        $this->validatorService = $validatorService;
        $this->appraisalReturnData = $appraisalReturnData;
        $this->validatorErrorReturnData = $validatorErrorReturnData;
    }

    public function getUserAppraisal() : JsonResponse
    {
        if ($this->appraisalService->checkAppraisalData()) {
            return $this->appraisalReturnData->returnWithoutData('Appraisal Data not found');
        }

        $data = $this->appraisalService->getAppraisal();
        $middlemark = $this->appraisalService->getMiddlemark();
        $collection = AppraisalResource::collection($data);
        return $this->appraisalReturnData->returnDataWithCustomField(
            'Appraisal Data found',
            $collection,
            ['middlemark' => $middlemark]
        );
    }

    public function createUserAppraisal(Request $request) : JsonResponse
    {
        $validated = $this->validatorService->globalValidation($request, $this->fields);

        if ($validated->fails()) {
            return $this->validatorErrorReturnData->returnData($validated);
        }
        $subject = $this->appraisalService->checkSubjectOnUser($request);
        if (!empty($subject)) {
            return $this->appraisalReturnData->returnWithoutData('This subject: ' . $subject . ' already added');
        }

        $this->appraisalService->addAppraisal($request);
        $this->appraisalService->changeMiddlemark();

        return $this->appraisalReturnData->returnCreateData('Subject added successfully');
    }
}
