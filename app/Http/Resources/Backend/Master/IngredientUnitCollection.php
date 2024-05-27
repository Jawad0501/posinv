<?php

namespace App\Http\Resources\Backend\Master;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IngredientUnitCollection extends ResourceCollection
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
                return new IngredientUnitResource($data);
            }),
        ];
    }
}
