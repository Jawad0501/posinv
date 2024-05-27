<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'image' => uploaded_file($this->image),
            'ip_address' => $this->ip_address,
            'last_login' => $this->last_login,
            'permissions' => $this->role?->permissions->pluck('slug'),
        ];
    }
}
