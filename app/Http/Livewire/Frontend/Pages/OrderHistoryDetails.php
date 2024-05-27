<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

class OrderHistoryDetails extends Component
{
    public $order;

    public function mount($invoice)
    {
        $this->order = Order::query()->with('orderDetails', 'orderDetails.food:id,name,image', 'orderDetails.addons', 'orderDetails.addons.addon')->where('user_id', auth()->id())->where('invoice', $invoice)->firstOrFail();
    }

    #[Title('Order Details')]
    public function render()
    {
        return view('livewire.frontend.pages.order-history-details');
    }
}
