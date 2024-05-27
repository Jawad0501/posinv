<?php

namespace App\Http\Resources\Backend\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'invoice' => $this->invoice,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'occasion' => $this->occasion,
            'special_request' => $this->special_request,
            'total_person' => $this->total_person,
            'expected_date' => format_date($this->expected_date),
            'expected_time' => date('h:i A', strtotime($this->expected_time)),
            'created_at' => format_date($this->created_at, true),
            'phone' => $this->phone,
        ];
    }
}
