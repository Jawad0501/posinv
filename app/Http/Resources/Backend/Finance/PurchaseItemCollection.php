<?php

namespace App\Http\Resources\Backend\Finance;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseItemCollection extends ResourceCollection
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
                    'unit_price' => $data->unit_price,
                    'quantity_amount' => $data->quantity_amount,
                    'total' => $data->total,
                    'expire_date' => $data->expire_date,
                ];
            }),
        ];
    }
}
