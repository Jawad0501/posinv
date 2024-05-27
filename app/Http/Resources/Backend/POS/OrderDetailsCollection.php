<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderDetailsCollection extends ResourceCollection
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
                    'food_id' => $data->food_id,
                    'variant_id' => $data->variant_id,
                    'processing_time' => $data->processing_time,
                    'status' => $data->status,
                    'price' => $data->price,
                    'quantity' => $data->quantity,
                    'vat' => $data->vat,
                    'total_price' => $data->total_price,
                    'note' => $data->note,
                    'food' => [
                        'name' => $data->food?->name,
                        'image' => uploaded_file($data->food?->image),
                    ],
                    'variant' => [
                        'name' => $data->variant?->name,
                    ],
                    'addons' => new OrderAddonDetailsCollection($data->addons),
                ];
            }),
        ];
    }
}
