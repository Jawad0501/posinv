<?php

namespace App\Http\Resources\Backend\Production;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductionUnitCreateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $request;

        return [
            'data' => $this->collection->transform(function ($data) {
                return $data;

                return [
                    'foods' => $data->foods,
                    'ingredients' => $data->ingredients,
                ];
            }),
        ];
    }
}
