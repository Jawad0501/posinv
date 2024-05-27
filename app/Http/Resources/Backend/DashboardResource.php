<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
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
            'pending_orders' => $this->pending_orders,
            'success_orders' => $this->success_orders,
            'cancel_orders' => $this->cancel_orders,
            'daily_orders' => $this->daily_orders,
            'weekly_orders' => $this->weekly_orders,
            'monthly_orders' => $this->monthly_orders,
            'yearly_orders' => $this->yearly_orders,
            'total_orders' => $this->total_orders,
            'daily_sales' => $this->daily_sales,
            'weekly_sales' => $this->weekly_sales,
            'monthly_sales' => $this->monthly_sales,
            'yearly_sales' => $this->yearly_sales,
            'total_sales' => $this->total_sales,
            'order_history' => new DashboardOrderHistroyCollection($this->order_history),
            'online_orders' => new DashboardOrderHistroyCollection($this->online_orders),
            'top_items' => new DashboardTopItemCollection($this->top_items),
        ];
    }
}
