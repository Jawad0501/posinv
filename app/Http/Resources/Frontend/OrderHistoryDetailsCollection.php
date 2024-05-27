<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderHistoryDetailsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($data) {
                return [
                    'menu' => $data->food?->name,
                    'variant' => $data->variant?->name,
                    'processing_time' => $data->processing_time,
                    'status' => $data->status,
                    'price' => $data->price,
                    'quantity' => $data->quantity,
                    'vat' => $data->vat,
                    'total_price' => $data->total_price,
                    'note' => $data->note,
                    'addons' => new OrderHistoryDetailsAddonCollection($data->addons),
                ];
            }),
        ];
    }
}
