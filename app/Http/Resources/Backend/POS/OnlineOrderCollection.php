<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OnlineOrderCollection extends ResourceCollection
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
                    'invoice' => $data->invoice,
                    'grand_total' => $data->grand_total,
                    'date_time' => $data->created_at->diffForHumans(),
                    'user' => [
                        'name' => $data->user?->full_name,
                        'image' => uploaded_file($data->user?->image),
                    ],
                ];
            }),
        ];
    }
}
