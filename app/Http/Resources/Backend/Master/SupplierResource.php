<?php

namespace App\Http\Resources\Backend\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'reference' => $this->reference,
            'address' => $this->address,
            'id_card_front' => uploaded_file($this->id_card_front),
            'id_card_back' => uploaded_file($this->id_card_back),
            'status' => $this->status,
        ];
    }
}
