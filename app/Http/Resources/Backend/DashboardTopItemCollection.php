<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DashboardTopItemCollection extends ResourceCollection
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
                    'name' => $data->name,
                    'price' => $data->price,
                    'total_sold_price' => $data->order_details_sum_total_price,
                    'total_ordered' => $data->order_details_sum_quantity,
                ];
            }),
        ];
    }
}
