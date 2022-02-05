<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonalsDataResource;
use App\Models\PersonalsData;
use App\Providers\DatabaseProvider;
use App\Providers\ValidatorProvider;
use App\Services\PersonalService;
use App\Services\ValidatorService;
use Illuminate\Http\Request;

class PersonalDataController extends Controller
{
    private $personalService, $validatorService;

    public function __construct(ValidatorService $validatorService, PersonalService $personalService)
    {
        $this->personalService = $personalService;
        $this->validatorService = $validatorService;
    }


    public function getPersonalData()
    {
        /** Проверка на существование данных [false - нет, true - есть] */
        if (!$this->personalService->checkPersonalData()) {
            return response()->json([
                'code' => 200,
                'message' => 'Personal Data not found',
                'data' => []
            ], 200);
        }

        $data = $this->personalService->getPersonalData();

        return response()->json([
            'code' => 200,
            'message' => ' Personal Data found',
            'data' => PersonalsDataResource::collection($data)
        ], 200);
    }

    public function postPersonalData(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return response()->json([
                "code" => 422,
                "message" => "Validation error",
                "error" => $validated->errors(),

            ], 422);
        }

        if ($this->personalService->checkPersonalData()) {
            return response()->json([
                'code' => 200,
                'message' => 'Personal Data already exists',
            ], 200);
        }

        $this->personalService->addPersonalData($request);

        return response()->json([
            'code' => 201,
            'message' => 'Personal data has been created.'
        ], 201);
    }

    public function patchPersonalData(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return response()->json([
                "code" => 422,
                "message" => "Validation error",
                "error" => $validated->errors(),
            ], 422);
        }

        if (!$this->personalService->checkPersonalData()) {
            return response()->json([
                'code' => 200,
                'message' => 'Personal Data not found'
            ], 200);
        }


        $this->personalService->patchPersonalData($request);

        return response()->json([
            'code' => 200,
            'message' => 'Personal data has been updated'
        ], 200);
    }
}
