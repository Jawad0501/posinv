<?php

namespace App\Http\Resources\Backend\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
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
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'name' => $this->name,
            'purchase_price' => $this->purchase_price,
            'alert_qty' => $this->alert_qty,
            'code' => $this->code,
        ];
    }
}
