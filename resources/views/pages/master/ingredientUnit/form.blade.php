<x-form-modal
    :title="isset($ingredientUnit) ? 'Update product unit':'Add new product unit'"
    action="{{ isset($ingredientUnit) ? route('ingredient-unit.update', $ingredientUnit->id) : route('ingredient-unit.store')}}"
    :button="isset($ingredientUnit) ? 'Update':'Submit'"
    id="form"
>
    @isset($ingredientUnit)
        @method('PUT')
    @endisset

    <x-form-group
        name="name"
        placeholder="Product unit name"
        :value="$ingredientUnit->name ?? ''"
    />

    <x-form-group
        name="note"
        for="description"
        isType="textarea"
        rows="2"
        placeholder="Product unit description"
        :required="false"
    >
        {{ $ingredientUnit->description ?? '' }}
    </x-form-group>

</x-form-modal>
