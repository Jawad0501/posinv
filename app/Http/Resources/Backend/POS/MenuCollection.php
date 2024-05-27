<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MenuCollection extends ResourceCollection
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
                    'name' => $data->name,
                    'slug' => $data->slug,
                    'image' => uploaded_file($data->image),
                    'price' => $data->price,
                    'tax_vat' => $data->tax_vat,
                    'calorie' => $data->calorie,
                    'description' => $data->description,
                    'allergies' => new MenuAllergyCollection($data->allergies),
                    'addons' => new MenuAddonCollection($data->addons),
                    'variants' => new MenuVarinatCollection($data->variants),
                    'categories' => $data->categories->pluck('id'),
                    // 'variant'     => new MenuVarinatResource($data->variant)
                ];
            }),
        ];
    }
}
