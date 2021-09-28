<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalsDataResource extends JsonResource
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
            'first_name'=>$this->first_name,
            'middle_name'=>$this->middle_name,
            'last_name'=>$this->last_name,
            'phone' => $this->phone,
            'orphan'=>$this->orphan,
            'childhood_disabled'=>$this->childhood_disabled,
            'the_large_family'=>$this->the_large_family,
            'hostel_for_students'=>$this->hostel_for_students,
            
        ];
    }
}
