<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TrainingSessionUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return Arr::whereNotNull([
            'name' => $this->name,
            'gym'  => $this->pivot->gym->name,
            'date' => $this->pivot->date,
            'time' => $this->pivot->time,
        ]);
    }
}
