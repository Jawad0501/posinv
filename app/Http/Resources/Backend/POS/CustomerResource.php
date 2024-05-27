<?php

namespace App\Http\Resources\Backend\POS;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'delivery_address' => $this->address_book !== null && count($this->address_book) > 0 ? $this->address_book[0]->location : '',
            'no_of_visit' => $this->orders_count,
            'last_visit' => $this->orders->count() > 0 ? format_date($this->orders[0]->created_at, true) : null,
            'points_acquired' => $this->rewards_available,
            'used_points' => $this->payments_sum_rewards,
            'customer_id' => $this->customer_id,
            'discount' => $this->discount,
        ];
    }
}
