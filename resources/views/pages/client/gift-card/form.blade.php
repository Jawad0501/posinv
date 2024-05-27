<x-form-modal
    :title="isset($giftCard) ? 'Update gift card':'Gift card payment'"
    action="{{ isset($giftCard) ? route('client.gift-card.update', $giftCard->id) : route('client.gift-card.store')}}"
    :button="isset($giftCard) ? 'Update':'Submit'"
    id="form"
>
    @isset($giftCard)
        @method('PUT')
    @endisset

    <x-form-group name="customer" isType="select" class="select2">
        <option value="">Select customer</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" @selected(isset($giftCard) && $user->id == $giftCard->user_id)>{{ $user->full_name ?? $user->customer_id }}</option>
        @endforeach
    </x-form-group>

    <x-form-group name="amount" placeholder="Enter amount..." :value="$giftCard->amount ?? ''"/>

    {{-- <x-form-group
        name="card_name"
        placeholder="Enter card name..."
        :value="$giftCard->name ?? ''"
        column="col-lg-6"
    />
    <x-form-group
        name="card_number"
        placeholder="Example: 4242424242424242"
        :value="$giftCard->number ?? ''"
        column="col-lg-6"
        type="number"
    />
    <x-form-group
        name="expiry_month"
        placeholder="Example: 08"
        :value="$giftCard->expiry_month ?? ''"
        column="col-lg-4"
        type="number"
    />
    <x-form-group
        name="expiry_year"
        placeholder="Example: 2028"
        :value="$giftCard->expiry_year ?? ''"
        column="col-lg-4"
        type="number"
    />
    <x-form-group
        name="cvc"
        placeholder="Example: 123"
        :value="$giftCard->cvc ?? ''"
        column="col-lg-4"
        type="number"
    /> --}}

</x-form-modal>
