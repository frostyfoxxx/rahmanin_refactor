<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * Класс-ресурс для вывода оценок
 * @property string $subject Название предметов
 * @property int $appraisal Оценки за предмет
 */
class AppraisalResource extends JsonResource
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
            'subject' => $this->subject,
            'appraisal' => $this->appraisal
        ];
    }
}
