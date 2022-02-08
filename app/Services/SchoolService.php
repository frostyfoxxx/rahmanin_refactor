<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolService
{
    /**
     * Проверка на сщуествование данных о школе
     * @return bool
     */
    public function checkSchoolData()
    {
        $user = auth('sanctum')->user()->id;
        $data = School::query()->where('users_id', $user)->get();
        if ($data->isEmpty()) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * Получение данных о школе
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getSchoolData()
    {
        $user = auth('sanctum')->user()->id;
        return School::query()->where('users_id', $user)->get();
    }

    /**
     * Добавление данных о школе
     * @param Request $request
     * @return void
     */
    public function createSchoolData(Request $request)
    {
        $user = auth('sanctum')->user()->id;

        School::create([
            'school_name' => $request->input('school_name'),
            'number_of_classes' => $request->input('number_of_classes'),
            'year_of_ending' => $request->input('year_of_ending'),
            'number_of_certificate' => $request->input('number_of_certificate'),
            'users_id' => $user
        ]);
    }

    /**
     * Изменения данных о школе
     * @param Request $request
     * @return void
     */
    public function updateSchoolData(Request $request)
    {
        $user = auth('sanctum')->user()->id;

        $updatedData = School::query()->where('users_id', $user)->first();
        $updatedData->school_name = $request->input('school_name');
        $updatedData->number_of_classes = $request->input('number_of_classes');
        $updatedData->year_of_ending = $request->input('year_of_ending');
        $updatedData->number_of_certificate = $request->input('number_of_certificate');
        $updatedData->save();
    }
}
