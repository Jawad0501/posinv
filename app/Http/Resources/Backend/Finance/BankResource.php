<?php

namespace App\Http\Resources\Backend\Finance;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
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
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'branch_name' => $this->branch_name,
            'balance' => $this->balance,
            'signature_image' => uploaded_file($this->signature_image),
        ];
    }
}
