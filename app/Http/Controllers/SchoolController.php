<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Providers\DatabaseProvider;
use App\Providers\ValidatorProvider;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function getSchool()
    {
        $data = DatabaseProvider::checkExistData('GET', School::class, 'No data was found for this user');
        if ($data['results']) {
            return $data['response'];
        }

        return DatabaseProvider::getData($data['response'], SchoolResource::class);
    }

    public function createSchoolData(Request $request)
    {
        $validated = ValidatorProvider::globalValidation($request->all());

        if ($validated->fails()) {
            return ValidatorProvider::errorResponse($validated);
        }

        $check = DatabaseProvider::checkExistData('POST', School::class, 'School data has already been added by the user');
        if ($check['results']) {
            return $check['response'];
        }

        DatabaseProvider::addOnTable($request->all(), School::class);

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
