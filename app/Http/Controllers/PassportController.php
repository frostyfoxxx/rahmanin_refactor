<?php

namespace App\Http\Controllers;

use App\Http\Resources\PassportResource;
use App\Services\PassportService;
use App\Services\ValidatorService;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    private $validatorService, $passportService;

    public function __construct(PassportService $passportService, ValidatorService $validatorService)
    {
        $this->passportService = $passportService;
        $this->validatorService = $validatorService;
    }

    public function getPassportData()
    {
        if (!$this->passportService->checkPassportData()) {
            return response()->json([
                'code' => 200,
                'message' => 'Passport Data not found',
                'data' => []
            ], 200);
        }

        $data = $this->passportService->getPassportData();

        return response()->json([
            'code' => 200,
            'message' => 'Passport Data are found',
            'data' => PassportResource::collection($data)
        ], 200);
    }

    public function createPassportData(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error',
                'error' => $validated->errors()
            ], 422);
        }


        if ($this->passportService->checkPassportData()) {
            return response()->json([
                'code' => 200,
                'message' => 'Passport Data already exists',
            ], 200);
        }

        $this->passportService->createPassportData($request);

        return response()->json([
            'data' => [
                'code' => 201,
                'message' => 'Passports data has been created.'
            ]
        ], 201);
    }

    public function updatePassportData(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error',
                'error' => $validated->errors()
            ], 422);
        }

        if (!$this->passportService->checkPassportData()) {
            return response()->json([
                'code' => 200,
                'message' => 'Passport Data not found',
                'data' => []
            ], 200);
        }

        $this->passportService->updatePassportData($request);

        return response()->json([
            'code' => 200,
            'message' => 'Passports data has been updated'
        ], 201);
    }
}
