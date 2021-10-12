<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorProvider extends ServiceProvider
{
    // TODO: Поле 'phone_number' нуждается в правиле  'unique:users,phone_number' для регистрации, но не нуждается для авторизации
    private static $rules = [
        'phone_number' => ['required', 'numeric', 'digits:11'],
        'email' => ['required', 'email', 'unique:users,email'],
        'first_name' => ['required', 'string'],
        'last_name' => ['required', 'string'],
        'phone' => ['required', 'numeric', 'digits:11'],
        'document_number' => ['required', 'string', 'max:10'],
        'password' => ['required', 'string', 'min:6'],
        'series' => 'required|string|digits:4',
        'number' => 'required|string|digits:6',
        'date_of_issue' => 'required|date_format:Y-m-d',
        'issued_by' => 'required|string|max:255',
        'date_of_birth' => 'required|date_format:Y-m-d',
        'gender' => 'required|string|max:255',
        'place_of_birth' => 'required|string|max:255',
        'registration_address' => 'required|string|max:255',
        'lack_of_citizenship' => 'required|boolean',
        'school_name' => 'required|string|max:255',
        'number_of_classes' => 'required|integer',
        'year_of_ending' => 'required|date_format:Y',
        'number_of_certificate' => 'required|numeric|digits:14',
    ];

    public static function globalValidation(array $req, $options = null)
    {
        $fields = [];

        foreach (self::$rules as $key => $value) {
            if (array_key_exists($key, $req)) {
                $fields[$key] = $value;
            }
        }

        if ($options) {
            foreach ($options as $key => $value) {
                $fields[$key] = $value;
            }
        }

        return Validator::make($req, $fields);
    }

    public static function errorResponse($valid)
    {
        return response()->json([
            "error" => [
                "code" => 422,
                "message" => "Validation error",
                "error" => $valid->errors(),
            ]
        ], 422);
    }
}
