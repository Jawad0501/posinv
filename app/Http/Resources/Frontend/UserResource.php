<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'is_verified' => $this->hasVerifiedEmail(),
            'verify_field' => $this->verify_field,
            'rewards_available' => $this->rewards_available,
            'image' => uploaded_file($this->image),
        ];
    }
}
