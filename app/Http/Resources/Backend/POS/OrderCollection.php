<?php

namespace App\Http\Resources\Backend\POS;

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
                    'type' => $data->type,
                    'invoice' => $data->invoice,
                    'token_no' => $data->token_no,
                    'delivery_type' => $data->delivery_type,
                    'customer_name' => $data->user?->full_name ?? $data->user?->customer_id,
                    'tables' => new OrderTableCollection($data->tables),
                ];
            }),
        ];
    }
}
