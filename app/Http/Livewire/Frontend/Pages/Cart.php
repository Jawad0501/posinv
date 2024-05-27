<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Enum\OrderDeliveryType;
use App\Enum\OrderStatus;
use App\Enum\PaymentMethod;
use App\Http\Controllers\Frontend\PaymentController;
use App\Models\Addon;
use App\Models\Coupon;
use App\Models\Food;
use App\Models\Order;
use App\Models\Tablelayout;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Cart extends Component
{
    public $show = false;

    public $addressKey = 0;

    public $coupon = null;

    public $order_note;

    public $coupon_code;

    public $use_reward = false;

    public $shipping_method;

    public $payment_method;

    public $total_person = 1;

    #[Title('Cart')]
    public function render()
    {
        $shipping_methods = [
            (object) [
                'label' => ucfirst(OrderDeliveryType::DELIVERY->value).' Delivery',
                'value' => OrderDeliveryType::DELIVERY->value,
                'image' => 'home-delivery.jpg',
                'desc' => 'Payment after delivery',
            ],
            (object) [
                'label' => ucfirst(OrderDeliveryType::PICKUP->value),
                'value' => OrderDeliveryType::PICKUP->value,
                'image' => 'home-delivery.jpg',
                'desc' => 'Payment after delivery',
            ],
        ];

        $payment_methods = [];
        $payment_methods[] = [
            'label' => PaymentMethod::CASH->value.' on Delivery',
            'value' => PaymentMethod::CASH->value,
            'image' => 'cash-on-delivery.jpg',
            'desc' => 'Payment after delivery',
        ];
        if (config('services.stripe.enable')) {
            $payment_methods[] = [
                'label' => PaymentMethod::STRIPE->value,
                'value' => PaymentMethod::STRIPE->value,
                'image' => 'stripe.png',
                'desc' => 'Payment using stripe',
            ];
        }
        if (config('paypal.enable')) {
            $payment_methods[] = [
                'label' => PaymentMethod::PAYPAL->value,
                'value' => PaymentMethod::PAYPAL->value,
                'image' => 'paypal.png',
                'desc' => 'Payment using paypal',
            ];
        }

        return view('livewire.frontend.pages.cart', compact('payment_methods', 'shipping_methods'));
    }

    #[On('updateAddressKey')]
    public function updateAddressKey($addressKey)
    {
        $this->addressKey = $addressKey['addressKey'];
    }

    /**
     * apply coupon code
     */
    public function applyCoupon()
    {
        $this->validate(['coupon_code' => 'required|string']);

        $coupon = Coupon::where('code', $this->coupon_code)->where('expire_date', '>=', date('Y-m-d'))->where('status', true)->first();
        if (! $coupon) {
            $this->coupon_code = null;
            $this->dispatch('alert', 'Coupon code not valid, please try to valid coupon code', 'error');
        } else {
            if ($coupon->discount_type == 'fixed') {
                $discount = $coupon->discount;
            } else {
                $sub_total = 0;
                foreach (session()->get('cart') as $cart) {
                    $sub_total += $cart['price'] * $cart['quantity'];
                }

                $discount = ($coupon->discount / 100) * $sub_total;
            }

            $this->coupon = [
                'type' => $coupon->code,
                'discount' => $discount,
                'code' => $coupon->code,
            ];

            $this->coupon_code = null;
            $this->dispatch('alert', 'Coupon applied successfully');
        }
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

    public function resetForm()
    {
        $this->addressKey = 0;
        $this->coupon = null;
        $this->order_note = null;
        $this->coupon_code = null;
        $this->use_reward = false;
        $this->shipping_method = null;
        $this->payment_method = null;

        session()->forget('cart');
        $this->dispatch('cart_added');
    }

    #[On('checkout')]
    public function checkout()
    {
        $address = (auth()->user()->address_book !== null && isset(auth()->user()->address_book[$this->addressKey])) ? auth()->user()->address_book[$this->addressKey] : null;
        if (empty($this->shipping_method) || empty($this->payment_method) || $address == null) {
            $this->dispatch('alert', 'Please select shipping method, payment method & address.', 'error');
        } else {
            $discount = isset($this->coupon['discount']) ? $this->coupon['discount'] : 0;

            $service_charge = setting('service_charge');
            $rewards = auth()->user()->rewards_available;
            $delivery_charge = $this->shipping_method == 'delivery' ? setting('delivery_charge') : 0;
            $reward_amount = $this->use_reward ? ($rewards * setting('reward_exchange_rate')) : 0;
            $sub_total = (cartDataGrandTotal(cartData()) - $discount);
            $grand_total = round(($sub_total + $service_charge + $delivery_charge) - $reward_amount, 2);

            $trx_id = getTrx();

            $payment = new PaymentController;
            $message = '';
            $redirect = '';
            $btc_wallet = null;

            if ($this->payment_method == PaymentMethod::STRIPE->value && config('services.stripe.enable')) {
                $response = $payment->stripe($grand_total, $trx_id);

                if (isset($response->error)) {
                    $message = $response->message;
                } else {
                    $redirect = $response->session->url;
                    $btc_wallet = $response?->session?->id;
                }
            }
            if ($this->payment_method == PaymentMethod::PAYPAL->value && config('paypal.enable')) {
                $response = $payment->paypal($grand_total, $trx_id);
                if (isset($response['id']) && $response['id'] != null) {
                    $btc_wallet = $response['id'];
                    $isRel = false;
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            $redirect = $links['href'];
                            $isRel = true;
                        }
                    }
                    if (! $isRel) {
                        $message = 'Something went wrong, please try again.';
                    }
                } else {
                    $message = 'Something went wrong, please try again.';
                }
            }

            if ($message != '') {
                $this->dispatch('alert', $message, 'error');

                return;
            }
            if ($reward_amount > 0) {
                auth()->user()->fill(['rewards_available' => 0])->save();
            }

            $order = Order::create([
                'user_id' => auth()->user()->id,
                'type' => 'Online',
                'order_by' => auth()->user()->full_name,
                'discount' => $discount,
                'rewards_amount' => $reward_amount,
                'service_charge' => $service_charge,
                'delivery_charge' => $delivery_charge,
                'delivery_type' => $this->shipping_method,
                'address' => $address,
                'note' => $this->order_note,
                'status' => session()->has('table_number') ? OrderStatus::PROCESSING->value : OrderStatus::PENDING->value,
                'seen_time' => session()->has('table_number') ? date('Y-m-d H:i:s') : null,
                'date' => date('Y-m-d'),
            ]);

            foreach (cartData() as $item) {
                $menu = Food::where('slug', $item['slug'])->first();
                $variant = Variant::find($item['variant_id'], ['id', 'price']);
                if ($menu) {
                    $price = $variant->price ?? $menu->price;
                    $total = $price * $item['quantity'];
                    $total_vat = $menu->tax_vat * $item['quantity'];
                    $tax_vat = ($total / 100) * $total_vat;

                    $orderDetails = $order->orderDetails()->create([
                        'food_id' => $menu->id,
                        'variant_id' => $variant->id,
                        'processing_time' => $menu->processing_time,
                        'price' => $price,
                        'quantity' => $item['quantity'],
                        'vat' => $total_vat,
                        'total_price' => ($total + $tax_vat),
                    ]);

                    foreach ($item['addons'] as $itemAddon) {
                        $addon = Addon::find($itemAddon['id']);
                        if ($addon) {
                            $orderDetails->addons()->create([
                                'addon_id' => $addon->id,
                                'price' => $addon->price,
                                'quantity' => $itemAddon['quantity'],
                            ]);
                        }
                    }
                }
            }

            $order->update([
                'invoice' => generate_invoice($order->id),
                'grand_total' => $grand_total,
                'token_no' => sprintf('%s%03s', '', DB::table('orders')->whereDate('created_at', date('Y-m-d'))->count()),
            ]);

            $order->payment()->create([
                'user_id' => auth()->id(),
                'method' => $this->payment_method,
                'reward_amount' => $reward_amount,
                'rewards' => $reward_amount > 0 ? $rewards : 0,
                'give_amount' => $grand_total,
                'grand_total' => $grand_total,
                'change_amount' => 0,
                'trx' => $trx_id,
                'btc_wallet' => $btc_wallet,
            ]);

            if (session()->has('table_number')) {
                $order->tables()->create([
                    'table_id' => session()->get('table_number'),
                    'total_person' => $this->total_person,
                ]);
                Tablelayout::query()->find(session()->get('table_number'))->decrement('available', $this->total_person);
            }

            if (! in_array($this->payment_method, [PaymentMethod::STRIPE->value, PaymentMethod::PAYPAL->value])) {
                $this->resetForm();

                return $this->redirect(route('payment.callback', ['status' => 'success', 'trx_id' => $trx_id]));
            } else {
                $this->resetForm();

                return $this->dispatch('checkoutDone', $redirect);
            }
        }
    }
}
