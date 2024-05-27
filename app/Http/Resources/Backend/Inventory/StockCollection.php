<?php

namespace App\Http\Resources\Backend\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StockCollection extends ResourceCollection
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
                    'qty_amount' => $data->qty_amount,
                    'ingredient' => [
                        'id' => $data->ingredient?->id,
                        'name' => $data->ingredient?->name,
                        'code' => $data->ingredient?->code,
                        'alert_qty' => $data->ingredient?->alert_qty,
                        'category' => $data->ingredient?->category,
                        'unit' => $data->ingredient?->unit,
                    ],
                ];
            }),
        ];
    }
}
