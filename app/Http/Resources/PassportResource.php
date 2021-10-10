<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PassportResource extends JsonResource
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
            'series'=> $this->series,
            'number'=> $this->number,
            'date_of_issue'=> $this->date_of_issue,
            'issued_by'=> $this->issued_by,
            'date_of_birth'=> $this->date_of_birth,
            'gender'=> $this->gender,
            'place_of_birth'=> $this->place_of_birth,
            'registration_address'=> $this->registration_address,
            'lack_of_citizenship'=> ($this->lack_of_citizenship) ? true : false
        ];
    }
}
