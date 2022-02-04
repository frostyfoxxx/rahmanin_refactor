<?php

namespace App\Services;


use App\Models\PersonalsData;
use Illuminate\Http\Request;


class PersonalService
{
    /**
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
}
