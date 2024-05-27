<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderTableCollection extends ResourceCollection
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
                    'table_id' => $data->table_id,
                    'table_number' => $data->table?->number,
                    'total_person' => $data->total_person,
                ];
            }),
        ];
    }
}
