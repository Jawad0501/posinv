<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = [];
        foreach ($this->orderDetails as $key => $orderDetails) {
            $items[$key] = [
                'name' => $orderDetails->food?->name,
                'quantity' => $orderDetails->quantity,
                'image' => uploaded_file($orderDetails->food?->image),
                'variant' => $orderDetails->variant?->name,
            ];
            $addons = [];
            foreach ($orderDetails->addons as $addon) {
                $addons[] = [
                    'name' => $addon?->addon->name,
                    'quantity' => $addon?->quantity,
                ];
            }
            $items[$key]['addons'] = $addons;

        }

        return [
            'invoice' => $this->invoice ?? '',
            'status' => $this->status,
            'type' => $this->type,
            'delivery_type' => $this->delivery_type,
            'address' => $this->address,
            'grand_total' => $this->grand_total,
            'date_time' => format_date($this->created_at, true),
            // 'processing_time' => orderAvailableTime($this->created_at, max($this->orderDetails->pluck('processing_time')->toArray())),
            'running_time' => orderRunningTime($this->seen_time),
            'items' => $items,
        ];
    }
}
