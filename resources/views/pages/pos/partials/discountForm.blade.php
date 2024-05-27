@php
    $discount      = session()->has('pos_discount') ? session()->get('pos_discount')['amount'] : null;
    $discount_type = session()->has('pos_discount') ? session()->get('pos_discount')['discount_type'] : null;
@endphp

<x-form-modal title="Discount" button="Submit" action="{{ route('pos.cart.discount') }}" id="form">
    <x-form-group
        name="amount"
        placeholder="Enter discount amount"
        :value="$discount"
    />

    <x-form-group name="discount_type" isType="select">
        <option value="fixed" @selected($discount_type == 'fixed')>Fixed</option>
        <option value="percentage" @selected($discount_type == 'percentage')>Percentage</option>
    </x-form-group>

</x-form-modal>
