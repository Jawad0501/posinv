<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TablelayoutCollection extends ResourceCollection
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
                    'name' => $data->name,
                    'number' => $data->number,
                    'capacity' => $data->capacity,
                    'available' => $data->available,
                    'image' => uploaded_file($data->image),
                    'orders' => new TablelayoutOrderCollection($data->orders),
                ];
            }),
        ];
    }
}
