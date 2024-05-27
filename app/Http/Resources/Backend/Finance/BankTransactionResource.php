<?php

namespace App\Http\Resources\Backend\Finance;

use Illuminate\Http\Resources\Json\JsonResource;

class BankTransactionResource extends JsonResource
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
            'withdraw_deposite_id' => $this->withdraw_deposite_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'decsription' => $this->decsription,
            'date' => format_date($this->date),
            'bank' => [
                'id' => $this->id,
                'name' => $this->bank?->name,
            ],
        ];
    }
}
