<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
/**
 * Класс-ресурс для данных о школе
 * @property string $school_name Название школы
 * @property int $number_of_classes Количество законченных классов
 * @property int $year_of_ending Год окончания школы
 * @property string $number_of_certificate Номер аттестата
 * @property int $number_of_photo Количество фотографий
 * @property string $version_of_the_certificate Тип аттестата
 */
class SchoolResource extends JsonResource
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
            'school_name' => $this->school_name,
            'number_of_classes' => $this->number_of_classes,
            'year_of_ending' => $this->year_of_ending,
            'number_of_certificate' => $this->number_of_certificate,
            'number_of_photo' => $this->number_of_photo,
            'version_of_the_certificate' => $this->version_of_the_certificate,
        ];
    }
}
