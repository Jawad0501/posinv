<?php

namespace App\Http\Resources\Backend\Master;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IngredientCollection extends ResourceCollection
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
                    'category_name' => $data->category?->name,
                    'unit_name' => $data->unit?->name,
                    'name' => $data->name,
                    'purchase_price' => $data->purchase_price,
                    'alert_qty' => $data->alert_qty,
                    'code' => $data->code,
                ];
            }),
        ];
    }
}
