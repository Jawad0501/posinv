<?php

namespace App\Http\Resources\Backend\Order;

use App\Http\Resources\Backend\POS\OrderDetailsCollection;
use App\Http\Resources\Backend\POS\OrderTableCollection;
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
            'processing_time' => $this->processing_time,
            'available_time' => $this->available_time,
            'order_by' => $this->order_by,
            'discount' => $this->discount,
            'rewards_amount' => $this->rewards_amount,
            'service_charge' => $this->service_charge,
            'delivery_charge' => $this->delivery_charge,
            'grand_total' => $this->grand_total,
            'delivery_type' => $this->delivery_type,
            'address' => $this->address,
            'note' => $this->note,
            'different_address' => $this->different_address,
            'tables' => new OrderTableCollection($this->tables),
            'customer' => $this->user === null ? [] : [
                'name' => $this->user?->full_name,
                'email' => $this->user?->email,
                'phone' => $this->user?->phone,
                'delivery_address' => $this->user?->delivery_address,
            ],
            'payment' => $this->payment === null ? [] : [
                'method' => $this->payment?->method,
                'reward_amount' => $this->payment?->reward_amount,
                'give_amount' => $this->payment?->give_amount,
                'change_amount' => $this->payment?->change_amount,
                'grand_total' => $this->payment?->grand_total,
            ],
            'orderDetails' => new OrderDetailsCollection($this->orderDetails),
        ];
    }
}
