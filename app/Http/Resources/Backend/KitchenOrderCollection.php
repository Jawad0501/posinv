<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KitchenOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($data) {
                $tables = [];
                foreach ($data->tables as $table) {
                    $tables[] = ['id' => $table->id, 'number' => $table->table?->number];
                }

                return [
                    'id' => $data->id,
                    'invoice' => $data->invoice,
                    'token_no' => $data->token_no,
                    'type' => $data->type,
                    'delivery_type' => $data->delivery_type,
                    'customer_name' => $data->user?->full_name ?? $data->user?->customer_id,
                    // 'available_time' => orderAvailableTime($data->created_at, max($data->orderDetails->pluck('processing_time')->toArray())),
                    'running_time' => orderRunningTime($data->seen_time),
                    'status' => $data->status,
                    'tables' => $tables,
                    'orderDetails' => new KitchenOrderDetailsCollection($data->orderDetails),
                ];
            }),
        ];
    }
}
