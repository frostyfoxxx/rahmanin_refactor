<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Services\SchoolService;
use App\Services\ValidatorService;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    private $validatorService, $schoolService;

    public function __construct(ValidatorService $validatorService, SchoolService $schoolService)
    {
        $this->validatorService = $validatorService;
        $this->schoolService = $schoolService;
    }

    public function getSchool()
    {
        if (!$this->schoolService->checkSchoolData()) {
            return response()->json([
                'code' => 200,
                'message' => 'School data not found',
                'data' => []
            ], 200);
        }

        $data = $this->schoolService->getSchoolData();

        return response()->json([
            'code' => 200,
            'message' => 'School Data are found',
            'data' => SchoolResource::collection($data)
        ], 200);
    }

    public function createSchoolData(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error',
                'error' => $validated->errors()
            ], 422);
        }

        if ($this->schoolService->checkSchoolData()) {
            return response()->json([
                'code' => 200,
                'message' => 'School Data already exists',
            ], 200);
        }

        $this->schoolService->createSchoolData($request);

        return response()->json([
            'data' => [
                'code' => 201,
                'message' => 'School data has been created.'
            ]
        ], 201);
    }

    public function updateSchoolData(Request $request)
    {
        $validated = ValidatorProvider::globalValidation($request->all());

        if ($validated->fails()) {
            return ValidatorProvider::errorResponse($validated);
        }

        $check = DatabaseProvider::checkExistData('PATCH', School::class, 'No data was found for this user');
        if ($check['results']) {
            return $check['response'];
        }

        DatabaseProvider::patchOnTable($request->all(), $check['response']);

        return response()->json([
            'code' => 200,
            'message' => 'Schools data has been updated'
        ], 201);
    }
}
