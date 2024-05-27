<?php

namespace App\Http\Resources\Frontend;

use App\Http\Resources\Backend\POS\MenuAllergyCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MenuCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($data) {
                return [
                    'name' => $data->name,
                    'slug' => $data->slug,
                    'image' => uploaded_file($data->image),
                    'price' => $data->price,
                    'tax_vat' => $data->tax_vat,
                    'calorie' => $data->calorie,
                    'description' => $data->description,
                    'allergies' => new MenuAllergyCollection($data->allergies),
                    'addons' => new AddonCollection($data->addons),
                    'variants' => new VariantCollection($data->variants),
                ];
            }),
        ];
    }
}
