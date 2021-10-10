<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
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
            'school_name'=>$this->school_name,
            'number_of_classes'=>$this->number_of_classes,
            'year_of_ending'=>$this->year_of_ending,
            'number_of_certificate'=>$this->number_of_certificate,
            'number_of_photo'=>$this->number_of_photo,
            'version_of_the_certificate'=>$this->version_of_the_certificate,
        ];
    }
}
