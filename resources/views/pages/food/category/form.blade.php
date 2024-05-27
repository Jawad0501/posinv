<x-form-modal
    :title="isset($category) ? 'Update category':'Add new category'"
    action="{{isset($category) ? route('food.category.update', $category->id) : route('food.category.store')}}"
    :button="isset($category) ? 'Update':'Submit'"
    id="fileForm"
>
    @isset($category)
        @method('PUT')
    @endisset

    <x-form-group name="name" placeholder="Enter category name" :value="$category->name ?? ''" />

    <x-form-group name="image" type="file" :required="false" accept="image/*" />

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_drinks" id="is_drinks" @isset($category) @checked($category->is_drinks) @endisset />
        <label class="form-check-label" for="is_drinks">Is Drinks</label>
    </div>

</x-form-modal>
