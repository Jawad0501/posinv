<x-form-modal
    :title="isset($mealPeriod) ? 'Update service':'Add new service'"
    action="{{ isset($mealPeriod) ? route('food.meal-period.update', $mealPeriod->id) : route('food.meal-period.store')}}"
    :button="isset($mealPeriod) ? 'Update':'Submit'"
    id="fileForm"
>
    @isset($mealPeriod)
        @method('PUT')
    @endisset

    <x-form-group name="name" placeholder="Enter service name" :value="$mealPeriod->name ?? ''" />

    <x-form-group type="time" name="start_time" for="time_slot[start_time]" placeholder="Enter start time" :value="$mealPeriod->time_slot?->start_time ?? ''" />
    <x-form-group type="time" name="end_time" for="time_slot[end_time]" placeholder="Enter end time" :value="$mealPeriod->time_slot->end_time ?? ''" />

</x-form-modal>
