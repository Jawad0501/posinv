<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryMenuCollection extends ResourceCollection
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
                    'slug' => $data->slug,
                    'image' => uploaded_file($data->image),
                    'foods' => new MenuCollection($data->foods),
                ];
            }),
        ];
    }
}
