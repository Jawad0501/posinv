<x-form-modal
    :title="isset($addon) ? 'Update addons/sub addons':'Add new addons/sub addons'"
    action="{{ isset($addon) ? route('food.addon.update', $addon->id) : route('food.addon.store')}}"
    :button="isset($addon) ? 'Update':'Submit'"
    id="form"
>
    @isset($addon) @method('PUT') @endisset

    <x-form-group name="addon" for="parent_id" isType="select" :required="false" class="select2">
        <option value="">Select a parent addon</option>
        @foreach ($addons as $item)
            <option value="{{ $item->id }}" @selected(isset($addon) && $addon->parent_id == $item->id)>{{ $item->name }}</option>
        @endforeach
    </x-form-group>
    <x-form-group name="name" placeholder="Enter name" :value="$addon->name ?? ''" />
    <x-form-group name="price" placeholder="Enter name" :value="$addon->price ?? ''" />
    <x-form-group name="title" placeholder="Enter title" :value="$addon->title ?? ''" :required="false" />

</x-form-modal>
