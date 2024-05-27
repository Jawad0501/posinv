<?php

namespace App\Http\Resources\Backend\Finance;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IncomeCollection extends ResourceCollection
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
                return new IncomeResource($data);
            }),
        ];
    }
}
