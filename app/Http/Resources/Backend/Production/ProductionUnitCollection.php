<?php

namespace App\Http\Resources\Backend\Production;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductionUnitCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($data) {
                return [
                    'id' => $data->id,
                    'food_name' => $data->food?->name,
                    'variant_name' => $data->variant?->name,
                    'price' => $data->items_sum_price,
                ];
            }),
        ];
    }
}
