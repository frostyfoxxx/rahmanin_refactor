<?php

namespace App\Services;


use App\Models\PersonalsData;
use Illuminate\Http\Request;


class PersonalService
{
    /**
     * Доблавение данных пользователя
     * @param Request $request - данные для добавления
     * @return void
     */
    public function addPersonalData(Request $request)
    {
        $id = auth('sanctum')->user()->id;
        PersonalsData::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'users_id' => $id
        ]);
    }

    /**
     * Проверка на существование таких данных
     * @return bool
     */
    public function checkPersonalData()
    {
        $result = null;
        $user = auth('sanctum')->user()->id;
        $personalData = PersonalsData::query()->where('users_id', $user)->get();
        if ($personalData->isEmpty()) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * Получение данных пользователя
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPersonalData()
    {
        $user = auth('sanctum')->user()->id;
        return PersonalsData::query()->where('users_id', $user)->get();
    }

    /**
     * Изменение данных пользователя
     * @param Request $request
     * @return void
     */
    public function patchPersonalData(Request $request)
    {
        $user = auth('sanctum')->user()->id;
        $personalData = PersonalsData::query()->where('users_id', $user)->first();

        $personalData->first_name = $request->input('first_name');
        $personalData->middle_name = $request->input('middle_name');
        $personalData->last_name = $request->input('last_name');
        $personalData->phone = $request->input('phone');
        $personalData->save();
    }
}