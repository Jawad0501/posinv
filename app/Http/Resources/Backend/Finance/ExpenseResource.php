<?php

namespace App\Http\Resources\Backend\Finance;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'person' => [
                'id' => $this->staff_id,
                'name' => $this->staff?->name,
            ],
            'category' => [
                'id' => $this->category_id,
                'name' => $this->category?->name,
            ],
            'date' => $this->date,
            'amount' => $this->amount,
            'note' => $this->note,
            'status' => $this->status,
        ];
    }
}
