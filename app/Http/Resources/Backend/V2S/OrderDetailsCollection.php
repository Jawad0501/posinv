<?php

namespace App\Http\Resources\Backend\V2S;

use App\Http\Resources\Backend\POS\OrderAddonDetailsCollection;
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
                    'status' => $data->status,
                    'quantity' => $data->quantity,
                    'processing_time' => $data->processing_time,
                    'menu_name' => $data->food->name,
                    'variant_name' => $data->variant->name ?? null,
                    'addons' => new OrderAddonDetailsCollection($data->addons),
                ];
            }),
        ];
    }
}
