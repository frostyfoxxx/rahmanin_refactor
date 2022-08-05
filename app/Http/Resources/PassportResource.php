<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * Класс-ресурс для паспортных данных
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
class PassportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'series' => $this->series,
            'number' => $this->number,
            'date_of_issue' => $this->date_of_issue,
            'issued_by' => $this->issued_by,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'place_of_birth' => $this->place_of_birth,
            'registration_address' => $this->registration_address,
            'lack_of_citizenship' => (bool)$this->lack_of_citizenship
        ];
    }
}
