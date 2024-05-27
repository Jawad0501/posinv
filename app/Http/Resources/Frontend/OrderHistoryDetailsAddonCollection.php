<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderHistoryDetailsAddonCollection extends ResourceCollection
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
                    'name' => $data->addon?->name,
                    'price' => $data->price,
                    'quantity' => $data->quantity,
                ];
            }),
        ];
    }
}
