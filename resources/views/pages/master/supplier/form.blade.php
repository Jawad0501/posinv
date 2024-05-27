<x-form-modal
    :title="isset($supplier) ? 'Update supplier':'Add new supplier'"
    action="{{ isset($supplier) ? route('supplier.update', $supplier->id) : route('supplier.store')}}"
    :button="isset($supplier) ? 'Update':'Submit'"
    id="fileForm"
    size="lg"
>
    @isset($supplier)
        @method('PUT')
    @endisset

    <div class="row">

        <x-form-group
            name="name"
            placeholder="Supplier name"
            :value="$supplier->name ?? ''"
            column="col-md-6"
        />

        <x-form-group
            name="email"
            type="email"
            placeholder="Supplier email"
            :value="$supplier->email ?? ''"
            column="col-md-6"
            :required=false
        />

        <x-form-group
            name="phone"
            placeholder="Supplier phone"
            :value="$supplier->phone ?? ''"
            column="col-md-6"
        />

        <x-form-group
            name="reference"
            placeholder="Supplier reference"
            :value="$supplier->reference ?? ''"
            column="col-md-6"
            :required="false"
        />

        <x-form-group
            name="address"
            isType="textarea"
            column="col-md-12"
            rows="2"
            placeholder="Supplier address"
            :required=false
        >
            {{ $supplier->address ?? '' }}
        </x-form-group>

        <x-form-group
            name="Driving licence/Passport Front side"
            for="id_card_front"
            column="col-md-6"
            type="file"
            :required="false"
        />

        <x-form-group
            name="Driving licence/Passport back side"
            for="id_card_back"
            column="col-md-6"
            type="file"
            :required="false"
        />

        <x-form-group
            name="advance_amount"
            for="advance_amount"
            column="col-md-12"
            :required="false"
        />

        <div class="col-md-6">
            <x-form-status :status="$supplier->status ?? true" />
        </div>

    </div>

</x-form-modal>
