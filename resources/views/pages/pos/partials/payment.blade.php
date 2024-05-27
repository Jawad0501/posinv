<x-form-modal title="Finalize Order" action="{{ route('pos.payment.store') }}" button="Submit" id="form">
    <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">

    {{-- @if ($order->user != null)
        <div class="mb-2 text-center">
            <span>Reward : {{ $order->user->rewards_available ?? 0 }},</span>
            <span>1 R = {{ convert_amount(setting('reward_exchange_rate')) }}, </span>
            <span>You get {{ convert_amount($finalize_order['reward_amount']) }} </span>
        </div>
    @endif --}}

    {{-- <div class="d-flex justify-content-between mb-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="use_rewards" name="use_rewards">
            <label class="form-check-label" for="use_rewards">Use Rewards</label>
        </div>
        <div>
            Payable Amount: <span id="payable_amount">{{ convert_amount($order->grand_total) }}</span>
        </div>
    </div> --}}

    {{-- <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="include_service_charge" name="include_service_charge" checked>
            <label class="form-check-label" for="include_service_charge">Include Service Charge</label>
        </div>
    </div> --}}
    {{-- @if ($order->user != null && $order->user->discount > 0)
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="customer_special_discount" name="customer_special_discount">
                <label class="form-check-label" for="customer_special_discount">Customer Special Discount({{ $order->user?->discount }}%)</label>
            </div>
        </div>
    @endif --}}

    {{-- @if ($order->user?->giftCards !== null)
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="use_gift_card" name="use_gift_card">
                <label class="form-check-label" for="use_gift_card">Use Gift Card</label>
            </div>
        </div>
    @endif --}}

    <div>
        Payable Amount: <span id="payable_amount">{{ convert_amount($order->grand_total) }}</span>
    </div>

    <x-form-group name="discount_amount" placeholder="Enter discount amount" :value="$order->discount"/>

    <x-form-group name="discount_type" isType="select">
        <option value="fixed">Fixed</option>
        <option value="percentage">Percentage</option>
    </x-form-group>

    <x-form-group name="payment_method" isType="select">
        @foreach ($status as $item)
            <option value="{{ $item }}">{{ $item }}</option>
        @endforeach
    </x-form-group>

    <x-form-group name="give_amount" placeholder="Enter given amount" :value="$order->grand_total" />

    <x-form-group name="change_amount" placeholder="Enter change amount" :value="0" :readonly="true" />

    <input type="hidden" name="finalize_order" value='@json($finalize_order)'>
</x-form-modal>
