<x-form-modal
    :title="isset($customer) ? 'Update customer':'Add new customer'"
    action="{{ isset($customer) ? route('client.customer.update', $customer->id) : route('client.customer.store')}}"
    :button="isset($customer) ? 'Update':'Submit'"
    id="fileForm"
    size="xl"
>
    @isset($customer)
        @method('PUT')
    @endisset

    <div class="row">

        <x-form-group
            name="first_name"
            placeholder="Enter first name..."
            :value="$customer->first_name ?? ''"
            column="col-md-6"
        />

        <x-form-group
            name="last_name"
            placeholder="Enter last name..."
            :value="$customer->last_name ?? ''"
            :required="false"
            column="col-md-6"
        />

        <x-form-group
            name="email"
            type="email"
            placeholder="Enter email..."
            :value="$customer->email ?? ''"
            :required="false"
            column="col-md-6"
        />

        <x-form-group
            name="phone"
            placeholder="Enter phone..."
            :value="$customer->phone ?? ''"
            column="col-md-6"
        />

        {{-- <x-form-group
            name="date_of_birth"
            placeholder="Enter date of birth..."
            :value="$customer->date_of_birth ?? ''"
            type="date"
            :required="false"
            column="col-md-6"
        />

        <x-form-group
            name="date_of_anniversary"
            placeholder="Enter date of anniversary..."
            :value="$customer->date_of_anniversary ?? ''"
            class="datepicker"
            :required="false"
            column="col-md-6"
        /> --}}

        <x-form-group
            name="discount"
            question="count in percentage"
            placeholder="Enter discount"
            :value="$customer->discount ?? 0"
            column="col-md-6"
        />
        <x-form-group
            name="address"
            placeholder="Enter address"
            :value="isset($customer) && $customer->address_book !== null ? $customer->address_book[0]->location : ''"
            column="col-md-6"
        />

        @if(!isset($customer))
            <x-form-group
                name="password"
                type="password"
                placeholder="Enter valid password"
                column="col-md-6"
            />

            <x-form-group
                name="confirm password"
                for="password_confirmation"
                type="password"
                placeholder="Enter valid password"
                column="col-md-6"
            />
        @endif

        <x-form-group
            name="image"
            type="file"
            :required="false"
            accept="image/*"
            data-show-image="show_profile_image"
            column="col-md-12"
        />

        <div class="col-md-12">
            <div class="text-center mb-3">
                <img src="{{ isset($customer) ?  uploaded_file($customer->image) : '' }}" class="img-fluid" id="show_profile_image"/>
            </div>
        </div>

        <div class="col-12">
            <x-form-status :status="$customer->status ?? true" />
        </div>

    </div>
</x-form-modal>
