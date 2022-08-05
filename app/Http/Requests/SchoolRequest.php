<?php

namespace App\Http\Requests;

/**
 * Класс реквеста данных о школе
 * @property string $school_name Название школы
 * @property int $number_of_classes Количество законченных классов
 * @property int $year_of_ending Год окончания школы
 * @property string $number_of_certificate Номер аттестата
 */
class SchoolRequest extends ApiRequest
{
    /**
     * Метод для правил валидации
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'school_name' => ['required', 'string'],
            'number_of_classes' => ['required', 'integer', 'in:9,11'],
            'year_of_ending' => ['required', 'date_format:Y'],
            'number_of_certificate' => ['required', 'numeric'],
        ];
    }
}
