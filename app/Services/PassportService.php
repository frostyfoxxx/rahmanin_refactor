<?php

namespace App\Services;

use App\Models\Passport;
use Illuminate\Http\Request;

class PassportService
{
    /**
     * Проверка на существование данных
     * @return bool
     */
    public function checkPassportData()
    {
        $users = auth('sanctum')->user()->id;

        $passport = Passport::query()->where('users_id', $users)->get();
        if ($passport->isEmpty()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Получение паспортных данных
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPassportData()
    {
        $users = auth('sanctum')->user()->id;

        return Passport::query()->where('users_id', $users)->get();
    }

    /**
     * Добавление паспортных данных
     * @param Request $request - данные для добавления
     * @return void
     */
    public function createPassportData(Request $request)
    {
        $users = auth('sanctum')->user()->id;

        Passport::create([
            'series' => $request->input('series'),
            'number' => $request->input('number'),
            'date_of_issue' => $request->input('date_of_issue'),
            'issued_by' => $request->input('issued_by'),
            'date_of_birth' => $request->input('date_of_birth'),
            'gender' => $request->input('gender'),
            'place_of_birth' => $request->input('place_of_birth'),
            'registration_address' => $request->input('registration_address'),
            'lack_of_citizenship' => $request->input('lack_of_citizenship'),
            'users_id' => $users
        ]);
    }

    /**
     * Обновление паспортных данных
     * @param Request $request - паспортные данные
     * @return void
     */
    public function updatePassportData(Request $request)
    {
        $users = auth('sanctum')->user()->id;

        $passportData = Passport::query()->where('users_id', $users)->first();

        $passportData->series = $request->input('series');
        $passportData->number = $request->input('number');
        $passportData->date_of_issue = $request->input('date_of_issue');
        $passportData->issued_by = $request->input('issued_by');
        $passportData->date_of_birth = $request->input('date_of_birth');
        $passportData->gender = $request->input('gender');
        $passportData->place_of_birth = $request->input('place_of_birth');
        $passportData->registration_address = $request->input('registration_address');
        $passportData->lack_of_citizenship = $request->input('lack_of_citizenship');
        $passportData->save();
    }
}
