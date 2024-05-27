<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'invoice' => $this->invoice,
            'status' => $this->status,
            'type' => $this->type,
            'running_time' => orderRunningTime($this->seen_time),
            'order_by' => $this->order_by,
            'discount' => $this->discount,
            'rewards_amount' => $this->rewards_amount,
            'service_charge' => $this->service_charge,
            'delivery_charge' => $this->delivery_charge,
            'grand_total' => $this->grand_total,
            'delivery_type' => $this->delivery_type,
            'address' => $this->address,
            'note' => $this->note,
            'customer' => [
                'id' => $this->user?->id,
                'name' => $this->user?->full_name ?? $this->user?->customer_id,
                'discount' => $this->user?->discount,
            ],
            'rider' => [
                'id' => $this->rider?->id,
                'name' => $this->rider?->full_name,
            ],
            'order_details' => new OrderDetailsCollection($this->orderDetails),
            'tables' => new OrderTableCollection($this->tables),
        ];
    }
}
