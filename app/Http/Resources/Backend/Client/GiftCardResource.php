<?php

namespace App\Http\Resources\Backend\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status,
            'trx' => $this->trx,
            'btc_wallet' => $this->btc_wallet,
            'customer' => [
                'name' => $this->user?->full_name ?? $this->user?->customer_id,
                'email' => $this->user?->email,
                'phone' => $this->user?->phone,
            ],
        ];
    }
}
