<?php

namespace App\Http\Resources\Backend\Food;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => uploaded_file($this->image),
            'price' => $this->price,
            'calorie' => $this->calorie,
            'processing_time' => $this->processing_time,
            'tax_vat' => $this->tax_vat,
            'description' => $this->description,
            'status' => $this->status,
            'ingredient_id' => $this->ingredient_id,
            'sku' => $this->sku,
            'weight' => $this->weight,
            'gtin' => $this->gtin,
            'online_item_visibility' => $this->online_item_visibility,
            'sellable' => $this->sellable,
            'mealPeriods' => new MealPeriodCollection($this->mealPeriods),
            'addons' => new AddonCollection($this->addons),
            'allergies' => new AllergyCollection($this->allergies),
            'categories' => new CategoryCollection($this->categories),
            'ingredient' => [
                'id' => $this->ingredient?->id,
                'name' => $this->ingredient?->name,
            ],
        ];
    }
}
