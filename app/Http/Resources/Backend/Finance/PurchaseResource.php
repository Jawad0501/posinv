<?php

namespace App\Http\Resources\Backend\Finance;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'supplier_id' => $this->supplier_id,
            'bank_id' => $this->bank_id,
            'reference_no' => $this->reference_no,
            'total_amount' => $this->total_amount,
            'shipping_charge' => $this->shipping_charge,
            'discount_amount' => $this->discount_amount,
            'paid_amount' => $this->paid_amount,
            'status' => $this->status,
            'date' => $this->date,
            'payment_type' => $this->payment_type,
            'details' => $this->details,
            'supplier' => [
                'id' => $this->supplier?->id,
                'name' => $this->supplier?->name,
            ],
            'bank' => [
                'id' => $this->bank?->id,
                'name' => $this->bank?->name,
            ],
            'items' => new PurchaseItemCollection($this->whenLoaded('items')),
        ];
    }
}
