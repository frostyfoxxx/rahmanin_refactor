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
        'password' => ['required'],
        'first_name' => ['required', 'string'],
        'last_name' => ['required', 'string'],
        'phone' => ['required', 'numeric', 'digits:11'],
        'document_number' => ['required', 'string', 'max:10'],
        'password' => ['required', 'string', 'min:6'],
    ];

    public static function globalValidation($req, $options = null)
    {
        // array_keys(%)
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
