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

    public function __construct(ValidatorService $validatorService, PersonalService $personalService) {
        $this->personalService = $personalService;
        $this->validatorService = $validatorService;
    }

    public function getPersonalData() {
        $data = DatabaseProvider::checkExistData('GET', PersonalsData::class, 'No data was found for this user');
        if ($data['results']) {
            return $data['response'];
        }

        return DatabaseProvider::getData($data['response'], PersonalsDataResource::class);
    }

    public function postPersonalData(Request $request)
    {
        $validated = $this->validatorService->globalValidation($request);

        if ($validated->fails()) {
            return response()->json([
                "error" => [
                    "code" => 422,
                    "message" => "Validation error",
                    "error" => $validated->errors(),
                ]
            ], 422);
        }

        $check = DatabaseProvider::checkExistData('POST', PersonalsData::class, 'Personal data has already been added by the user');
        if ($check['results']) {
            return $check['response'];
        }

        $this->personalService->addPersonalData($request);

        return response()->json([
            'data' => [
                'code' => 201,
                'message' => 'Personal data has been created.'
            ]
        ], 201);
    }

    public function patchPersonalData(Request $request)
    {
        $validated = ValidatorProvider::globalValidation($request->all());

        if ($validated->fails()) {
            return ValidatorProvider::errorResponse($validated);
        }

        $check = DatabaseProvider::checkExistData('PATCH', PersonalsData::class, 'No data was found for this user');
        if ($check['results']) {
            return $check['response'];
        }

        DatabaseProvider::patchOnTable($request->all(), $check['response']);

        return response()->json([
            'code' => 200,
            'message' => 'Personal data has been updated'
        ], 201);
    }
}
