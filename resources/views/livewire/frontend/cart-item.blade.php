@php
    $items       = cartData();
    $discount    = isset($coupon['discount']) ? $coupon['discount'] : 0;
    $coupon_code = isset($coupon['code']) ? $coupon['code'] : null;
    $service     = count(cartData()) > 0 ? setting('service_charge') : 0;
    $index       = 0;
    $sub_total   = 0;
    $tax_vat     = 0;
    $grand_total = $service;
    $grand_total -= $discount;
    $grand_total += $shipping_method == 'delivery' ? setting('delivery_charge') : 0;
    $count       = 0;
@endphp

<div class="bg-white rounded-md shadow">
    <p class="font-semibold p-4 text-[18px] border-b">Your Items</p>

    <div class="max-h-[300px] overflow-y-auto">
        @if (count($items))
            @foreach ($items as $key => $item)
                @php
                    $menu    = DB::table('food')->where('slug', $item['slug'])->first(['price','tax_vat']);
                    $variantPrice = DB::table('variants')->where('id', $item['variant_id'])->value('price');

                    $price = $variantPrice ?? $menu->price;
                    $total = $price * $item['quantity'];

                    $total_vat = ($total / 100) * ($menu->tax_vat * $item['quantity']);
                    $tax_vat   += $total_vat;
                    $sub_total += $total;
                    $grand_total += $total;

                    foreach ($item['addons'] as $addon) {
                        $addonPrice   = DB::table('addons')->where('id', $addon['quantity'])->value('price');
                        $addons_price = $addonPrice * $addon['quantity'];
                        $sub_total    += $addons_price;
                        $grand_total  += $addons_price;
                    }
                @endphp
                <div class="flex justify-between border-b p-4">
                    <div class="flex items-center">
                        <div>
                            <p>{{ $item['name'] }}</p>
                            <p class="text-xs">
                                <span>{{ $item['variant_name'] }}@if (count($item['addons'])), @endif</span>
                                @foreach ($item['addons'] as $addon)
                                    <span>{{ $addon['name'] }}{{ !$loop->last ? ',':'' }}</span>
                                @endforeach
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div>
                            <p>{{ convert_amount($total) }}</p>
                        </div>
                        <div class="flex items-center">
                            <button class="border w-6 h-6" wire:click="updateQuantity('{{ $key }}', false)">
                                <svg class="w-3 h-3 text-gray-300 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                            </button>
                            <input type="text" class="w-6 h-6 bg-gray-500 text-center text-white text-xs" readonly value="{{ $item['quantity'] }}">
                            <button class="border w-6 h-6" wire:click="updateQuantity('{{ $key }}')">
                                <svg class="w-3 h-3 text-gray-300 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center py-3">Your cart is empty!</p>
        @endif

    </div>

    @php $grand_total += $tax_vat; @endphp

    <div class="border-b p-4 space-y-3">
        <div class="border-b pb-3 space-y-1">
            <p class="text-sm">
                Subtotal
                <span class="float-right">{{ convert_amount($sub_total) }}</span>
            </p>
            <p class="text-sm">
                Service Charge
                <span class="float-right">{{ convert_amount($service) }}</span>
            </p>
            <p class="text-sm">
                Total Vat
                <span class="float-right">{{ convert_amount($tax_vat) }}</span>
            </p>
            <p class="text-sm">
                Delivery Charge
                <span class="float-right">{{ convert_amount($shipping_method == 'delivery' ? setting('delivery_charge') : 0) }}</span>
            </p>
            <p class="text-sm">
                Discount @if ($coupon_code !== null) ({{ $coupon_code }}) @endif
                <span class="float-right">- {{ convert_amount($discount) }}</span>
            </p>
            @if ($use_reward)
                @php
                    $rewards_amount = (int) auth()->user()?->rewards_available * (float) setting('reward_exchange_rate');
                    $grand_total    -= $rewards_amount;
                @endphp
                <p class="text-sm">
                    Rewards
                    <span class="float-right">- {{ convert_amount($rewards_amount) }}</span>
                </p>
            @endif
        </div>
        <div>
            <h6>
                <span class="font-medium">TO PAY</span>
                <span class="float-right">{{ convert_amount($grand_total) }}</span>
            </h6>
        </div>
    </div>

    <div class="border-b p-4 space-y-3">
        <a href="{{ route('cart') }}" wire:navigate class="bg-primary-500 w-full block py-4 text-center text-white rounded-md font-bold" @disabled(count($items) <= 0)>Checkout</a>
    </div>
</div>
