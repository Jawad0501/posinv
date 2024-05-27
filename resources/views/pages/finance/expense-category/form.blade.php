<x-form-modal 
    :title="isset($expenseCategory) ? 'Update expense category':'Add new expense category'" 
    action="{{ isset($expenseCategory) ? route('expense-category.update', $expenseCategory->id) : route('expense-category.store')}}"
    :button="isset($expenseCategory) ? 'Update':'Submit'"
    id="form"
>
    @isset($expenseCategory)
        @method('PUT')
    @endisset

    <x-form-group
        name="name"
        placeholder="Category name"
        :value="$expenseCategory->name ?? ''"
    />

</x-form-modal>