<?php

namespace App\Http\Resources\Backend\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReturnResource extends JsonResource
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
            'orderdetail_id' => $this->orderdetail_id,
            'quantity' => $this->quantity,
            'return_amount' => $this->return_amount,
        ];
    }
}
