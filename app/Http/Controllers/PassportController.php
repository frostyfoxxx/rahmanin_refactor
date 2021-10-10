<?php

namespace App\Http\Controllers;

use App\Http\Resources\PassportResource;
use App\Models\Passport;
use App\Providers\DatabaseProvider;
use App\Providers\ValidatorProvider;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    public function getPassportData()
    {
        $data = DatabaseProvider::checkExistData('GET', Passport::class, 'No data was found for this user');
        if ($data['results']) {
            return $data['response'];
        }

        return DatabaseProvider::getData($data['response'], PassportResource::class);
    }

    public function createPassportData(Request $request)
    {
        $validated = ValidatorProvider::globalValidation($request->all());

        if ($validated->fails()) {
            return ValidatorProvider::errorResponse($validated);
        }

        $checkExists = DatabaseProvider::checkExistData('POST', Passport::class, 'Passports data has already been added by the user');
        if ($checkExists['results']) {
            return $checkExists['response'];
        }

        DatabaseProvider::addOnTable($request->all(), Passport::class);

        return response()->json([
            'data' => [
                'code' => 201,
                'message' => 'Passports data has been created.'
            ]
        ], 201);
    }

    public function updatePassportData(Request $request)
    {
        $validated = ValidatorProvider::globalValidation($request->all());

        if ($validated->fails()) {
            return ValidatorProvider::errorResponse($validated);
        }

        $checkExists = DatabaseProvider::checkExistData('PATCH', Passport::class, 'No data was found for this user');
        if ($checkExists['results']) {
            return $checkExists['response'];
        }

        DatabaseProvider::patchOnTable($request->all(), $checkExists['response']);

        return response()->json([
            'code' => 200,
            'message' => 'Passports data has been updated'
        ], 201);
    }
}
