<x-form-modal
    :title="isset($customer) ? 'Update customer':'Add new customer'"
    action="{{ isset($customer) ? route('pos.customer.update', $customer->id) : route('pos.customer.store')}}"
    :button="isset($customer) ? 'Update':'Submit'"
    id="form"
>
    @isset($customer)
        @method('PUT')
    @endisset

    <x-form-group name="first_name" placeholder="Enter first name ..." :value="$customer->first_name ?? ''"/>
    <x-form-group name="last_name" placeholder="Enter last name ..." :value="$customer->last_name ?? ''" :required="false" />
    <x-form-group name="email" type="email" placeholder="Enter email..." :value="$customer->email ?? ''" :required="false" />
    <x-form-group name="phone" placeholder="Enter phone..." :value="$customer->phone ?? ''" />

    <x-form-group name="delivery_address" :required="false" isType="textarea" rows="2" placeholder="Enter delivery address...">{{ isset($customer) && $customer->address_book !== null ? $customer->address_book[0]->location : '' }}</x-form-group>

</x-form-modal>
