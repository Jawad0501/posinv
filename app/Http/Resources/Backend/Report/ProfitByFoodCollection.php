<?php

namespace App\Http\Resources\Backend\Report;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfitByFoodCollection extends ResourceCollection
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
                    'food_name' => $data->food?->name,
                    'order_sum_grand_total' => $data->order_sum_grand_total,
                ];
            }),
        ];
    }
}
