<x-form-modal 
    :title="isset($incomeCategory) ? 'Update income category':'Add new income category'" 
    action="{{ isset($incomeCategory) ? route('income-category.update', $incomeCategory->id) : route('income-category.store')}}"
    :button="isset($incomeCategory) ? 'Update':'Submit'"
    id="form"
>
    @isset($incomeCategory)
        @method('PUT')
    @endisset

    <x-form-group
        name="name"
        placeholder="Category name"
        :value="$incomeCategory->name ?? ''"
    />

</x-form-modal>