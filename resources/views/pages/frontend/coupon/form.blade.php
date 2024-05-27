<x-form-modal
    :title="isset($coupon) ? 'Update coupon':'Add new coupon'"
    action="{{ isset($coupon) ? route('frontend.coupon.update', $coupon->id) : route('frontend.coupon.store')}}"
    :button="isset($coupon) ? 'Update':'Submit'"
    id="fileForm"
    size="lg"
>
    @isset($coupon)
        @method('PUT')
    @endisset

    <x-form-group
        name="code"
        placeholder="Enter code..."
        :value="$coupon->code ?? ''"
    />

    <x-form-group
        name="discount_type"
        isType="select"
    >
        <option value="fixed">Fixed</option>
        <option value="percentage">Percentage</option>
    </x-form-group>

    <x-form-group
        name="discount"
        placeholder="Enter discount..."
        :value="$coupon->discount ?? ''"
    />

    <x-form-group
        name="expire_date"
        placeholder="Enter expire date..."
        :value="$coupon->expire_date ?? ''"
        class="datepicker"
    />

    <x-form-status :status="$coupon->status ?? true" />

</x-form-modal>
