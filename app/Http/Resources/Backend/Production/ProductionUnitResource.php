<?php

namespace App\Http\Resources\Backend\Production;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductionUnitResource extends JsonResource
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
            'food' => [
                'id' => $this->food?->id,
                'name' => $this->food?->name,
            ],
            'variant' => [
                'id' => $this->variant?->id,
                'name' => $this->variant?->name,
            ],
            'items' => new ProductionUnitItemCollection($this->items),
        ];
    }
}
