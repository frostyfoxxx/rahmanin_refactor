<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Validated extends Model
{
    private static $rules = [
        'phone_number' => ['required', 'string', 'max:11'],
        'email' => ['required', 'string', 'unique:users,email'],
        'password' => ['required'],
        'first_name' => ['required', 'string'],
        'last_name' => ['required', 'string'],
        'phone' => ['required', 'unique:users', 'string'],
        'document_number' => ['required', 'string', 'max:10'],
        'password' => ['required', 'string'],
    ];

    protected $fillable = [
        'phone_number', 'email', 'password'
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
