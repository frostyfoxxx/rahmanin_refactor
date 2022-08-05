<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Класс-ресурс для вывода информации о родителях
 * @property string $surname Фамилия
 * @property string $name Имя
 * @property string $patronymic Отчество
 * @property string $phone Контактный номер телефона
 * @author frostyfoxxx
 */
class ParentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'surname' => $this->surname,
            'name' => $this->name,
            'patronymic' => $this->patronymic,
            'phone' => $this->phone
        ];
    }
}
