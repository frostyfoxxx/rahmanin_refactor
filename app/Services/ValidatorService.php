<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorService
{

    private static $rules = [
        // Регистрация и Авторизация
        'phone_number' => ['required', 'numeric', 'digits:11'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'string', 'min:6'],
        // Персональные данные
        'phone' => ['required', 'numeric', 'digits:11'],
        'first_name' => ['required', 'string'],
        'last_name' => ['required', 'string'],
        'middle_name' => ['required', 'string'],
        // Паспортные данные
        'series' => 'required|string|digits:4',
        'number' => 'required|string|digits:6',
        'date_of_issue' => 'required|date_format:Y-m-d',
        'issued_by' => 'required|string|max:255',
        'date_of_birth' => 'required|date_format:Y-m-d',
        'gender' => 'required|string|max:255',
        'place_of_birth' => 'required|string|max:255',
        'registration_address' => 'required|string|max:255',
        'lack_of_citizenship' => 'required|boolean',
        // Данные о школе
        'school_name' => 'required|string|max:255',
        'number_of_classes' => 'required|integer',
        'year_of_ending' => 'required|date_format:Y',
        'number_of_certificate' => 'required|numeric|digits:14',
        // Школьные предметы
        "*.subject" => ['required', 'string'],
        '*.appraisal' => ['required', 'numeric', 'between:3,5']
    ];

    /**
     * @param Request $req - Объект с приходящими данными
     * @param array $fields - массив с полями
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function globalValidation(Request $req, array $fields)
    {
        foreach (self::$rules as $key => $value) {
            if (in_array($key, $fields)) {
                $validatorRules[$key] = $value;
            }
        }
        return Validator::make($req->all(), $validatorRules);
    }

}
