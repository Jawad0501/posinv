<?php

namespace App\Http\Resources\Backend\V2S;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
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
                    'invoice' => $data->invoice,
                    'user_name' => $data->user->full_name ?? null,
                    'available_time' => $data->available_time,
                    'order_details' => new OrderDetailsCollection($data->orderDetails),
                ];
            }),
        ];
    }
}
