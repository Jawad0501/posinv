<?php

namespace App\Http\Resources\Backend\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class StockAdjustmentResource extends JsonResource
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
            'staff_id' => $this->staff_id,
            'person_name' => $this->staff?->name,
            'reference_no' => $this->reference_no,
            'date' => $this->date,
            'note' => $this->note,
            'added_by' => $this->added_by,
            'items' => new StockAdjustmentItemCollection($this->items),
        ];
    }
}
