<?php

namespace App\Http\Resources\Backend\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableLayoutResource extends JsonResource
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
            'name' => $this->name,
            'number' => $this->number,
            'capacity' => $this->capacity,
            'available' => $this->available,
            'image' => uploaded_file($this->image),
            'status' => $this->status,
        ];
    }
}
