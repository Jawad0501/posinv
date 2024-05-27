<section class="section bg-light">
    <div class="container">
        <div class="flex flex-col xl:flex-row gap-5">
            <div class="xl:w-2/3 space-y-5">

                <livewire:frontend.cart-address />

                <div class="bg-white rounded-md p-5 shadow space-y-8">

                    <div class="space-y-3">
                        <p class="font-semibold text-md">Shipping Methods</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($shipping_methods as $item)
                                <div class="relative">
                                    <input class="peer hidden" wire:model.live="shipping_method" value="{{ $item->value }}" id="{{ $item->value }}" type="radio" name="shipping_method" />
                                    <span class="peer-checked:border-primary-700 absolute right-4 top-1/2 box-content block h-3 w-3 -translate-y-1/2 rounded-full border-8 border-primary-300 bg-white"></span>
                                    <label class="peer-checked:border-2 peer-checked:border-primary-700 peer-checked:bg-primary-50 flex cursor-pointer select-none rounded-lg border border-primary-300 p-4"
                                        for="{{ $item->value }}">
                                        <img class="w-14 object-contain" src="{{ Vite::image($item->image) }}" alt="" />
                                        <div class="ml-5">
                                            <span class="mt-2 font-semibold">{{ $item->label }}</span>
                                            <p class="text-slate-500 text-sm leading-6">{{ $item->desc }}</p>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="font-semibold text-md">Payment Methods</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ count($payment_methods) }} gap-4">
                            @foreach ($payment_methods as $item)
                                <div class="relative">
                                    <input class="peer hidden" wire:model='payment_method' value="{{ $item['value'] }}" id="{{ $item['value'] }}" type="radio" name="payment_method" />
                                    <span class="peer-checked:border-primary-700 absolute right-4 top-1/2 box-content block h-3 w-3 -translate-y-1/2 rounded-full border-8 border-primary-300 bg-white"></span>
                                    <label class="peer-checked:border-2 peer-checked:border-primary-700 peer-checked:bg-primary-50 flex cursor-pointer select-none rounded-lg border border-primary-300 p-4" for="{{ $item['value'] }}">
                                        <img class="w-14 h-14 object-contain" src="{{ Vite::image($item['image']) }}" alt="{{ $item['label'] }}" />
                                        <div class="ml-5">
                                            <span class="mt-2 font-semibold">{{ $item['label'] }}</span>
                                            <p class="text-slate-500 text-sm leading-6">{{ $item['desc'] }}</p>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="lg:w-2/3 space-y-4">
                        <form wire:submit="applyCoupon">
                            <div class="flex w-full flex-wrap items-stretch">
                                <input type="text" wire:model="coupon_code" @readonly($coupon !== null) class="border py-1 px-3 focus-visible:outline-none rounded-l w-4/5" placeholder="Enter promo code">
                                <button type="submit" class="bg-primary-500 py-1 px-3 rounded-r border-t border-b text-white w-1/5" wire:target="applyCoupon" wire:loading.attr="disabled" @disabled($coupon !== null)>APPLY</button>
                            </div>
                            @error('coupon_code')
                                <small class="invalid-feedback text-warning-500">{{ $message }}</small>
                            @enderror
                        </form>
                        <div class="flex w-full flex-wrap items-stretch">
                            <div class="w-1/5 bg-[#f8f9fa] border-l border-t border-b flex items-center justify-center">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                            </div>
                            <textarea wire:model="order_note" class="border py-1 px-3 focus-visible:outline-none rounded-r w-4/5" placeholder="Any suggestions? We will pass it on..."></textarea>
                        </div>
                        @if (session()->has('table_number'))
                            <x-frontend.form-group wire:model='total_person' label="total_person" type="number" min="1" placeholder="Enter total person" />
                        @endif
                        @if (auth()->check())
                            <div class="py-2">
                                <div class="relative">
                                    <input class="peer hidden" wire:model.live="use_reward" id="use_reward" type="checkbox" name="use_reward" />
                                    <span class="peer-checked:border-primary-700 absolute right-4 top-1/2 box-content block h-3 w-3 -translate-y-1/2 rounded-full border-8 border-primary-300 bg-white"></span>
                                    <label class="peer-checked:border-2 peer-checked:border-primary-700 peer-checked:bg-primary-50 flex cursor-pointer select-none rounded-lg border border-primary-300 p-4" for="use_reward">
                                        <img class="w-14 h-14 object-contain" src="{{ Vite::image('reward.png') }}" alt="Use Reward" />
                                        <div class="ml-5">
                                            <span class="mt-2 font-semibold">Use Reward</span>
                                            <p class="text-slate-500 text-sm leading-6">Use reward for transition</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="xl:w-1/3">
                <div class="sticky left-0 top-[130px]">
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

                        // rsort($items);
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
                            @guest
                                <button type="button" wire:click="set('show', true)" class="bg-primary-500 w-full block py-4 text-center text-white rounded-md font-bold">Checkout</button>
                            @else
                                <x-frontend.submit-button label="PAY {{ convert_amount($grand_total) }}" wire:click="checkout" wire:loading.attr="disabled" wire:loading.class="btn-loading" wire:target="checkout" :disabled="count($items) <= 0" />
                            @endguest
                            @guest
                                <div
                                    x-data
                                    class="fixed inset-0 z-[700] w-full h-full flex flex-col items-center justify-center bg-gray-600 bg-opacity-50"
                                    x-transition:enter="transition ease-out duration-1000"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    x-show="$wire.show"
                                >
                                    <div
                                        class="w-full transition-all transform sm:max-w-lg product-details-modal-content rounded-md"
                                        role="dialog"
                                        aria-modal="true"
                                        aria-labelledby="modal-headline"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 -translate-y-4 sm:translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-300"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-4 sm:translate-y-4"
                                        @click.away="$wire.set('show', false)"
                                    >
                                        <div class="shadow-sm bg-white rounded-md relative p-10">
                                            <div wire:click="set('show', false)" class="absolute top-2 right-4 bg-white w-10 h-10 rounded-full flex items-center justify-center z-[1] cursor-pointer shadow">
                                                <svg class="w-5 h-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>

                                            <div class="w-full h-100 space-y-5">
                                                <h1 class="text-xl md:text-2xl font-bold leading-tight mt-12">
                                                    Log in to your account
                                                </h1>

                                                <livewire:frontend.login-form redirect="/cart" />

                                                <p>
                                                    Need an account?
                                                    <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                                                        Create an account
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
