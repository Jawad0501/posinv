<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderHistoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($data) {
                return [
                    'names' => DB::table('food')->whereIn('id', $data->orderDetails?->pluck('food_id'))->pluck('name')->implode(' & '),
                    'invoice' => $data->invoice ?? '',
                    'items' => $data->orderDetails->count(),
                    'image' => uploaded_file($data->orderDetails->count() ? $data->orderDetails[0]->food?->image : null),
                    'date_time' => format_date($data->created_at, true),
                ];
            }),
        ];
    }
}
