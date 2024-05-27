<?php

namespace App\Http\Resources\Backend\Order;

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
                    'customer_name' => $data->user?->full_name,
                    'type' => $data->type,
                    'status' => $data->status,
                    'grand_total' => $data->grand_total,
                ];
            }),
        ];
    }
}
