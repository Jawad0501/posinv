<?php

namespace App\Http\Resources\Backend\Production;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductionUnitItemCollection extends ResourceCollection
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
                    'ingredient' => [
                        'id' => $data->ingredient?->id,
                        'name' => $data->ingredient?->name,
                    ],
                    'quantity' => $data->quantity,
                    'unit' => $data->unit,
                    'price' => $data->price,
                ];
            }),
        ];
    }
}
