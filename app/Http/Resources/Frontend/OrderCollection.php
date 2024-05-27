<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($data) {
                return new OrderResource($data);
            }),
        ];
    }
}
