<?php

namespace App\Http\Requests;

/**
 * Класс-реквест для персональных данных
 * @property int $phone - Номер телефона
 * @property string $first_name - Имя
 * @property string $middle_name - Отчество
 * @property string $last_name - Фамилия
 * @property bool $orphan - Сирота
 * @property bool $childhood_disabled - Инвалид детства
 * @property bool $the_large_family - Многодетная семья
 * @property bool $hostel_for_students - Нуждается в общежитии
 */
class PersonalDataRequest extends ApiRequest
{
    /**
     * Метод для правил валидации
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'numeric', 'digits:11'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'orphan' => ['boolean'],
            'childhood_disabled' => ['boolean'],
            'the_large_family' => ['boolean'],
            'hostel_for_students' => ['boolean']
        ];
    }
}
