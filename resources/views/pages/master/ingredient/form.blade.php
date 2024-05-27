<x-form-modal
    :title="isset($ingredient) ? 'Update Product':'Add New Product'"
    action="{{ isset($ingredient) ? route('ingredient.update', $ingredient->id) : route('ingredient.store')}}"
    :button="isset($ingredient) ? 'Update':'Submit'"
    id="form"
    size="lg"
>
    @isset($ingredient)
        @method('PUT')
    @endisset

    <div class="row">

        <x-form-group name="name" placeholder="Name" :value="$ingredient->name ?? ''" column="col-md-6" />

        <x-form-group
            name="purchase_price"
            placeholder="Purchase Price"
            :value="$ingredient->purchase_price ?? ''"
            column="col-md-6"
            :required="false"
        />
        <x-form-group
            name="category"
            isType="select"
            column="col-md-6"
            class="select2"
        >
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @isset($ingredient) @selected($ingredient->category_id == $category->id ? true:false) @endisset>{{ $category->name }}</option>
            @endforeach
        </x-form-group>

        <x-form-group
            name="alert_qty"
            placeholder="Alert Qty"
            :value="$ingredient->alert_qty ?? ''"
            column="col-md-6"
        />
        <x-form-group
            name="unit"
            isType="select"
            column="col-md-6"
            class="select2"
        >
            <option value="">Select unit</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" @isset($ingredient) @selected($ingredient->unit_id == $unit->id ? true:false) @endisset>{{ $unit->name }}</option>
            @endforeach
        </x-form-group>

        <!-- <x-form-group
            name="code"
            placeholder="Code"
            :value="$ingredient->code ?? ''"
            column="col-md-6"
            :required="false"
        /> -->

    </div>

</x-form-modal>
