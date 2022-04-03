<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *   schema="Validation",
 *   type="object",
 *   @OA\Property(
 *     property="field1",
 *     type="array",
 *     @OA\Items(
 *       example="The field1 field is required"
 *     )
 *   ),
 *   @OA\Property(
 *     property="field2",
 *     type="array",
 *     @OA\Items(
 *       example="The field2 must be a number."
 *     )
 *   ),
 * )
 */
class ValidatorService
{

    private static array $rules = [
        // Регистрация и Авторизация
        'phone_number' => ['required', 'numeric', 'digits:11'],
        'email' => ['email', 'unique:users,email'],
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
        '*.appraisal' => ['required', 'integer', 'between:3,5', 'digits:1']
    ];

    /**
     * @param Request $req
     * @param array $fields
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
