<x-form-modal
    :title="isset($review) ? 'Update review':'Add new review'"
    action="{{ isset($review) ? route('orders.review.update', $review->id) : route('orders.review.store')}}"
    :button="isset($review) ? 'Update':'Submit'"
    id="form"
    size="lg"
>
    @isset($review)
        @method('PUT')
    @endisset

    <x-form-group name="customer" isType="select">
        <option value="">Select customer</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" @isset($review) @selected($user->id == $review->user_id ? true : false) @endisset>{{ $user->full_name }}</option>
        @endforeach
    </x-form-group>

    <x-form-group name="menu_food" isType="select">
        <option value="">Select food</option>
        @foreach ($foods as $food)
            <option value="{{ $food->id }}" @isset($review) @selected($food->id == $review->food_id ? true : false) @endisset>{{ $food->name }}</option>
        @endforeach
    </x-form-group>

    <x-form-group name="rating" isType="select">
        <option value="">Select rating</option>
        @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}" @isset($review) @selected($review->rating == $i ? true : false) @endisset>{{ $i }}</option>
        @endfor
    </x-form-group>

    <x-form-group name="comment" isType="textarea" rows="2" placeholder="Enter comment...">{{$review->comment ?? ''}}</x-form-group>

    <x-form-status :status="$review->status ?? true" />

</x-form-modal>
