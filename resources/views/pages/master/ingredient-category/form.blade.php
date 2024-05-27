<x-form-modal 
    :title="isset($ingredientCategory) ? 'Update product category':'Add new product category'" 
    action="{{ isset($ingredientCategory) ? route('ingredient-category.update', $ingredientCategory->id) : route('ingredient-category.store')}}"
    :button="isset($ingredientCategory) ? 'Update':'Submit'"
    id="form"
>
    @isset($ingredientCategory)
        @method('PUT')
    @endisset

    <x-form-group
        name="name"
        placeholder="Product category name"
        :value="$ingredientCategory->name ?? ''"
    />

</x-form-modal>