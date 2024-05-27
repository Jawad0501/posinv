<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct(public $resource, public $show = true)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];
        if ($this->show) {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
                'occasion' => $this->occasion,
                'special_request' => $this->special_request,
            ];
        }

        return array_merge([
            'total_person' => $this->total_person,
            'expected_date' => format_date($this->expected_date),
            'expected_time' => date('h:i A', strtotime($this->expected_time)),
            'phone' => $this->phone,
            'invoice' => $this->invoice,
        ], $data);
    }
}
