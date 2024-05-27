<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public $variant;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $variant = null)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->variant = $variant;
    }

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
            'addons' => new MenuAddonCollection($this->addons),
            'variants' => new MenuVarinatCollection($this->variants),
            'variant' => new MenuVarinatResource($this->variant),
        ];
    }
}
