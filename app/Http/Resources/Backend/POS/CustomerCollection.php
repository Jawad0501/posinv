<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
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
                    'name' => $data->full_name,
                    'email' => $data->email,
                    'phone' => $data->phone,
                    'date_of_birth' => $data->date_of_birth,
                    'date_of_anniversary' => $data->date_of_anniversary,
                    'rewards' => $data->rewards,
                    'customer_id' => $data->customer_id,
                    'discount' => $data->discount,
                ];
            }),
        ];
    }
}
