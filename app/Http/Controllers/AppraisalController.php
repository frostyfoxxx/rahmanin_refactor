<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppraisalResource;
use App\Models\Appraisal;
use App\Providers\DatabaseProvider;
use App\Providers\ValidatorProvider;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{
    public function getUserApproisal()
    {
        $data = DatabaseProvider::checkExistData('GET', Appraisal::class, 'No data was found for this user');
        if ($data['results']) {
            return $data['response'];
        }
        $middlemark = Appraisal::getMiddlemark();
        return DatabaseProvider::getData($data['response'], AppraisalResource::class, ['middlemark' => $middlemark]);
    }

    public function createuserAppraisal(Request $request)
    {
        $validated = ValidatorProvider::globalValidation($request->all());

        if ($validated->fails()) {
            return ValidatorProvider::errorResponse($validated);
        }

        if (Appraisal::checkSubjectOnUser($request->all())) {
            return response()->json([
                'code' => 400,
                'message' => 'This subject has already been added'
            ], 400);
        }

        Appraisal::addAppraisal($request->all());
        Appraisal::changeMiddlemark();

        return response()->json([
            'code' => 201,
            'message' => 'Subject added successfully'
        ]);
    }
}
