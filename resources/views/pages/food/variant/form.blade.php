<x-form-modal
    :title="isset($variant) ? 'Update variant':'Add New variant'"
    :action="isset($variant) ? route('food.variant.update', $variant->id) : route('food.variant.store')"
    :button="isset($variant) ? 'Update':'Submit'"
    id="fileForm"
>
    @isset($variant)
        @method('PUT')
    @endisset

    <x-form-group name="menu" isType="select" class="select2">
        <option value="">Select menu</option>
        @foreach($foods as $food)
            <option value="{{ $food->id }}" @isset($variant) @selected($variant->food_id == $food->id) @endisset>{{ $food->name }}</option>
        @endforeach
    </x-form-group>

    <x-form-group
        name="name"
        placeholder="Enter variant Name"
        :value="$variant->name ?? ''"
    />

    <x-form-group
        name="price"
        placeholder="Enter variant price"
        :value="$variant->price ?? ''"
    />
</x-form-modal>
