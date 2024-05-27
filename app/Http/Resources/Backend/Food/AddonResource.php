<?php

namespace App\Http\Resources\Backend\Food;

use Illuminate\Http\Resources\Json\JsonResource;

class AddonResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'parent_name' => $this->addon?->name,
            'name' => $this->name,
            'price' => $this->price,
            'title' => $this->title,
            'status' => $this->status,
        ];
    }
}
