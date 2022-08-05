<?php

namespace App\Http\Requests;

/**
 * Класс-реквест для данных о родителях
 * @property string $surname Фамилия
 * @property string $name Имя
 * @property string $patronymic Отчество
 * @property string $phone Контактный номер телефона
 * @author frostyfoxx
 */
class ParentsRequest extends ApiRequest
{
    /**
     * Правила валидации для реквеста
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            '*' => ['required', 'array'],
            '*.surname' => ['required', 'string'],
            '*.name' => ['required', 'string'],
            '*.patronymic' => ['required', 'string'],
            '*.phone' => ['required', 'string']
        ];
    }
}
