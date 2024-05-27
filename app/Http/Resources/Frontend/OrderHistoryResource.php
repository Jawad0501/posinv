<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice' => $this->invoice,
            'status' => $this->status,
            'processing_time' => $this->processing_time,
            'available_time' => $this->available_time,
            'discount' => $this->discount,
            'rewards_amount' => $this->rewards_amount,
            'service_charge' => $this->service_charge,
            'delivery_charge' => $this->delivery_charge,
            'grand_total' => $this->grand_total,
            'delivery_type' => $this->delivery_type,
            'address' => $this->address,
            'note' => $this->note,
            'rewards' => $this->rewards,
            'date_time' => format_date($this->created_at, true),
            'orderDetails' => new OrderHistoryDetailsCollection($this->orderDetails),
        ];
    }
}
