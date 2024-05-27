<?php

namespace App\Http\Resources\Backend\Food;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
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
            'id' => $this->id,
            'food_id' => $this->food_id,
            'name' => $this->name,
            'price' => $this->price,
            'status' => $this->status,
        ];
    }
}
