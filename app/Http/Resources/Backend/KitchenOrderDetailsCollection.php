<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KitchenOrderDetailsCollection extends ResourceCollection
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
                    'status' => $data->status,
                    'food_name' => $data->food?->name,
                    'quantity' => $data->quantity,
                    'variant_name' => $data->variant?->name,
                    'note' => $data->note,
                    'addons' => new KitchenOrderDetailsAddonCollection($data->addons),
                ];
            }),
        ];
    }
}
