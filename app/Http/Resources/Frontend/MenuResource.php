<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'image' => uploaded_file($this->image),
            'price' => $this->price,
            'calorie' => $this->calorie,
            'processing_time' => $this->processing_time,
            'tax_vat' => $this->tax_vat,
            'description' => $this->description,
            'status' => $this->status,
            'ingredient_id' => $this->ingredient_id,
            'addons' => new AddonCollection($this->addons),
            'variants' => new VariantCollection($this->variants),
        ];
    }
}
