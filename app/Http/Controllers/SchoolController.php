<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\SchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * Метод получения данных о школе
     * @return JsonResponse
     */
    public function getSchool(): JsonResponse
    {
        $data = Auth::user()->schoolData ? Auth::user()->schoolData->all() : [];
        return response()->json([
            'code' => 200,
            'message' => 'School Data found',
            'data' => SchoolResource::collection($data)
        ])->setStatusCode(200);
    }

    /**
     * @param SchoolRequest $request
     * @return JsonResponse
     */
    public function createSchoolData(SchoolRequest $request): JsonResponse
    {
        if (Auth::user()->schoolData) {
            throw  new ApiException(300, 'School data already exists');
        }

        $schoolData = School::make($request->all());
        $schoolData->user_id = Auth::user()->id;
        $schoolData->save();

        return response()->json([
            'code' => 201,
            'message' => 'School data created success'
        ])->setStatusCode(201);
    }

    /**
     * Запрос на изменение данных о школе
     * @param SchoolRequest $request
     * @return JsonResponse
     */
    public function updateSchoolData(SchoolRequest $request): JsonResponse
    {
        /** @var School $schoolData */
        $schoolData = Auth::user()->schoolData;
        if (!$schoolData) {
            throw new ApiException(300, 'School data not found for updating');
        }

        $schoolData->school_name = $request->school_name;
        $schoolData->number_of_classes = $request->number_of_classes;
        $schoolData->year_of_ending = $request->year_of_ending;
        $schoolData->number_of_certificate = $request->number_of_certificate;
        $schoolData->save();

        return response()->json([
            'code' => 200,
            'message' => 'School Data has been updated'
        ])->setStatusCode(200);
    }
}
