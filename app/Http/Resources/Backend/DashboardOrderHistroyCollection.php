<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DashboardOrderHistroyCollection extends ResourceCollection
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
                    'date' => format_date($data->date),
                    'customer_name' => $data->user?->full_name,
                    'invoice' => $data->invoice,
                    'grand_total' => $data->grand_total,
                    'status' => $data->status,
                ];
            }),
        ];
    }
}
