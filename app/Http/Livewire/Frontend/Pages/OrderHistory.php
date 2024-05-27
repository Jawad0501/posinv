<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class OrderHistory extends Component
{
    use WithPagination;

    #[Title('Order History')]
    public function render()
    {
        $orders = Order::query()->with('orderDetails:id,order_id,food_id', 'orderDetails.food:id,name,image')->where('user_id', auth()->id())->latest('id')->paginate(12);

        return view('livewire.frontend.pages.order-history', compact('orders'));
    }
}
