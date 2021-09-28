<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorProvider extends ServiceProvider
{
    // TODO: Поле 'phone_number' нуждается в правиле  'unique:users,phone_number' для регистрации, но не нуждается для авторизации
    private static $rules = [
        'phone_number' => ['required', 'string', 'max:11'],
        'email' => ['required', 'string', 'unique:users,email'],
        'password' => ['required'],
        'first_name' => ['required', 'string'],
        'last_name' => ['required', 'string'],
        'phone' => ['required', 'string'],
        'document_number' => ['required', 'string', 'max:10'],
        'password' => ['required', 'string'],
    ];

    public static function globalValidation($req)
    {
        // array_keys(%)
        $fields = [];

        foreach (self::$rules as $key => $value) {
            if (array_key_exists($key, $req)) {
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
