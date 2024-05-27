<?php

namespace App\Http\Resources\Backend\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StockAdjustmentItemCollection extends ResourceCollection
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
                        'code' => $data->ingredient?->code,
                    ],
                    'quantity_amount' => $data->quantity_amount,
                    'consumption_status' => $data->consumption_status,
                ];
            }),
        ];
    }
}
