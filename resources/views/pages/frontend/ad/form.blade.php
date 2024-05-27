<x-form-modal
    :title="isset($ad) ? 'Update ad':'Add new ad'"
    action="{{ isset($ad) ? route('frontend.ad.update', $ad->id) : route('frontend.ad.store')}}"
    :button="isset($ad) ? 'Update':'Submit'"
    id="fileForm"
>
    @isset($ad) @method('PUT') @endisset

    <x-form-group name="title" placeholder="Enter title..." :value="$ad->title ?? ''" :required="false" />

    <x-form-group name="link" placeholder="Enter link..." :value="$ad->self ?? ''" :required="false" />

    <x-form-group name="type" isType="select" :required="false">
        <option value="">select type</option>
        <option value="self" @selected(isset($ad) && $ad->type == 'self')>self</option>
        <option value="blank" @selected(isset($ad) && $ad->type == 'blank')>blank</option>
    </x-form-group>


    <x-form-group name="image" type="file" accept="image/*" :required="!isset($ad)" />

    {{-- <x-form-status :status="$ad->status ?? true" /> --}}

</x-form-modal>
