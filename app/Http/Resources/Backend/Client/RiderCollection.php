<?php

namespace App\Http\Resources\Backend\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RiderCollection extends ResourceCollection
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
                ];
            }),
        ];
    }
}
