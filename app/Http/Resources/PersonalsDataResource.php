<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * Класс ресурс для персональных данных
 * @property int $phone - Номер телефона
 * @property string $first_name - Имя
 * @property string $middle_name - Отчество
 * @property string $last_name - Фамилия
 * @property bool $orphan - Сирота
 * @property bool $childhood_disabled - Инвалид детства
 * @property bool $the_large_family - Многодетная семья
 * @property bool $hostel_for_students - Нуждается в общежитии
 *
 * @OA\Schema(
 *   schema="personalData",
 *   type="object",
 *   @OA\Property(
 *     property="phone",
 *     type="string",
 *     example="88005553535"
 *   ),
 *   @OA\Property(
 *     property="first_name",
 *     type="string",
 *     example="Иванов"
 *   ),
 *   @OA\Property(
 *      property="middle_name",
 *      type="string",
 *      example="Иван"
 *    ),
 *    @OA\Property(
 *      property="last_name",
 *      type="string",
 *      example="Иванович"
 *     ),
 * )
 */
class PersonalsDataResource extends JsonResource
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
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'orphan' => $this->orphan,
            'childhood_disabled' => $this->childhood_disabled,
            'the_large_family' => $this->the_large_family,
            'hostel_for_students' => $this->hostel_for_students,
        ];
    }
}
