<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TablelayoutOrderCollection extends ResourceCollection
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
                    'total_person' => $data->total_person,
                    'invoice' => $data->order?->invoice,
                    'available_time' => $data->order?->available_time,
                    'time' => format_date($data->order?->created_at, true),
                ];
            }),
        ];
    }
}
