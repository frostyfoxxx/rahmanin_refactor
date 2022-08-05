<?php

namespace App\Http\Requests;

/**
 * Класс реквеста паспортных данных
 * @property int $series Серия паспорта
 * @property int $number Номер паспорта
 * @property string $date_of_issue Дата выдачи паспорта
 * @property string $issued_by Кем выдан паспорт
 * @property string $date_of_birth Дата рождения по паспорту
 * @property string $gender Пол по паспорту
 * @property string $place_of_birth Место рождения по паспорту
 * @property string $registration_address Место регистрации по паспорту
 * @property bool $lack_of_citizenship Нет гражданства РФ
 * @author frostyfoxxx
 */
class PassportDataRequest extends ApiRequest
{
    /**
     * Метод для правил валидации
     * @return string[][]
     */
    public function rules(): array
    {
        // TODO: Добавить условие required_if = lack_of_citizenship false
        return [
            'series' => ['required', 'numeric'],
            'number' => ['required', 'numeric'],
            'date_of_issue' => ['required', 'date_format:Y-m-d'],
            'issued_by' => ['required', 'string'],
            'date_of_birth' => ['required', 'date_format:Y-m-d'],
            'gender' => ['required', 'string'],
            'place_of_birth'=> ['required', 'string'],
            'registration_address' => ['required', 'string'],
            'lack_of_citizenship' => ['required', 'boolean']
        ];
    }
}
