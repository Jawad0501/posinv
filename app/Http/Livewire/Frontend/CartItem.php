<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Attributes\On;
use Livewire\Component;

class CartItem extends Component
{
    public $use_reward;

    public $shipping_method;

    public $payment_method;

    #[On('cart_added')]
    public function render()
    {
        return view('livewire.frontend.cart-item');
    }

    /**
     * Update car quantity data
     */
    public function updateQuantity($id, $increment = true)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            $increment ? $cart[$id]['quantity']++ : $cart[$id]['quantity']--;
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
        }
        session()->put('cart', $cart);
        $this->dispatch('cart_added');
    }
}
