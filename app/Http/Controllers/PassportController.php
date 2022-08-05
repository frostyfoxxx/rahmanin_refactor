<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\PassportDataRequest;
use App\Http\Resources\PassportResource;
use App\Models\Passport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
// TODO: Сделать swagger-doc

    /**
     * Метод получения паспортных данных
     * @return JsonResponse
     */
    public function getPassportData(): JsonResponse
    {
        $foundedData = Auth::user()->passportData ? Auth::user()->passportData->all() : [];
        return response()->json([
            'code' => 200,
            'message' => 'Passport Data found',
            'data' => PassportResource::collection($foundedData)
        ])->setStatusCode(200);
    }

    /**
     * Метод создания паспортных данных
     * @param PassportDataRequest $request
     * @return JsonResponse
     */
    public function createPassportData(PassportDataRequest $request): JsonResponse
    {
        if (Auth::user()->passportData) {
            throw  new ApiException(300, 'Passport data already exists');
        }

        $passportData = Passport::make($request->all());
        $passportData->user_id = Auth::user()->id;
        $passportData->save();

        return response()->json([
            'code' => 201,
            'message' => 'Passport data created success'
        ])->setStatusCode(201);
    }

    /**
     * Метод обновления паспортных данных
     * @param PassportDataRequest $request
     * @return JsonResponse
     */
    public function updatePassportData(PassportDataRequest $request): JsonResponse
    {
        /** @var Passport $passportData */
        $passportData = Auth::user()->passportData;
        if (!$passportData) {
            throw new ApiException(300, 'Passport data not found for updating');
        }

        $passportData->series = $request->series;
        $passportData->number = $request->number;
        $passportData->date_of_issue = $request->date_of_issue;
        $passportData->issued_by = $request->issued_by;
        $passportData->gender = $request->gender;
        $passportData->place_of_birth = $request->place_of_birth;
        $passportData->registration_address = $request->registration_address;
        $passportData->lack_of_citizenship = $request->lack_of_citizenship;
        $passportData->save();

        return response()->json([
            'code' => 200,
            'message' => 'Passport data has been updated'
        ], 200);
    }
}
