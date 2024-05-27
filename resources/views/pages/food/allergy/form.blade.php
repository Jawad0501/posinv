<x-form-modal
    :title="isset($allergy) ? 'Edit allergies':'Add new allergies'"
    action="{{ isset($allergy) ? route('food.allergy.update', $allergy->id) : route('food.allergy.store')}}"
    :button="isset($allergy) ? 'Update':'Submit'"
    id="fileForm"
>
    @isset($allergy)
        @method('PUT')
    @endisset

    <x-form-group name="name" placeholder="Enter allergy name" :value="$allergy->name ?? ''" />

    <x-form-group name="image" type="file" accept="image/*" />

</x-form-modal>
