<?php

namespace App\Http\Resources\Backend\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StockAdjustmentCollection extends ResourceCollection
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
                    'person_name' => $data->staff?->name,
                    'reference_no' => $data->reference_no,
                    'date' => $data->date,
                    'note' => $data->note,
                    'added_by' => $data->added_by,
                ];
            }),
        ];
    }
}
