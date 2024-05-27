<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Attributes\On;
use Livewire\Component;

class CartCount extends Component
{
    public $mobile;

    public $quantity = 0;

    public function mount($mobile = true)
    {
        $this->mobile = $mobile;
    }

    #[On('cart_added')]
    public function render()
    {
        $this->quantity = 0;
        foreach (cartData() as $cart) {
            $this->quantity += $cart['quantity'];
        }

        return view('livewire.frontend.cart-count');
    }
}
