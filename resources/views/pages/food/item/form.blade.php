<x-form-modal
    :title="isset($food) ? 'Update menu':'Add new menu'"
    action="{{isset($food) ? route('food.menu.update', $food->id) : route('food.menu.store')}}"
    :button="isset($food) ? 'Update':'Submit'"
    id="fileForm"
    size="xl"
>
    @isset($food)
        @method('PUT')
    @endisset

    <div class="row">

        <x-form-group
            name="categories"
            for="categories[]"
            isType="select"
            column="col-md-6"
            class="select2"
            multiple
        >
            <option value="">Select Category</option>
            @foreach ($data->categories as $category)
                <option value="{{ $category->id }}"
                    @isset($food)
                        @foreach ($food->categories as $fCategory)
                            @selected($fCategory->id == $category->id)
                        @endforeach
                    @endisset
                >{{ $category->name }}</option>
            @endforeach
        </x-form-group>

        <!-- <x-form-group
            name="ingredient_name"
            isType="select"
            :required="false"
            :column="isset($food) && $food->ingredient_id !== null ? 'col-md-6 is_drinks':'col-md-6 is_drinks d-none'"
            class="select2"
        >
            <option value="">Select ingredient</option>
            @foreach ($data->ingredients as $ingredient)
                <option value="{{ $ingredient->id }}"
                    @isset($food)
                        @selected($food->ingredient_id == $ingredient->id)
                    @endisset
                >{{ $ingredient->name }}</option>
            @endforeach
        </x-form-group> -->

        <x-form-group
            name="name"
            placeholder="Enter food name"
            :value="$food->name ?? ''"
            column="col-md-6"
        />

        <x-form-group
            name="size"
            isType="select"
            column="col-md-6"
            class="select2"
            tags="true"
            :required="false"
        >
            <option value="">Select size</option>
            @foreach ($data->sizes as $size)
                <option value="{{ $size }}" @selected(isset($food) && in_array($size, $food->variants->pluck('name')->toArray()))>{{ $size }}</option>
            @endforeach
        </x-form-group>

        <x-form-group
            name="price"
            placeholder="Enter food price"
            :value="$food->price ?? ''"
            column="col-md-6"
        />
        <x-form-group
            name="vat (%)"
            for="tax_vat"
            placeholder="Enter food vat"
            :value="$food->tax_vat ?? ''"
            column="col-md-6"
        />

        <x-form-group
            name="processing_time (count in minutes)"
            for="processing_time"
            type="number"
            placeholder="Enter food processing time"
            :value="$food->processing_time ?? null"
            :column="isset($food) && $food->ingredient_id !== null ? 'col-md-6 is_no_drinks d-none':'col-md-6 is_no_drinks'"
            autocomplete="off"
            :required="false"
        />
        <x-form-group
            name="calorie"
            placeholder="Enter food calorie"
            :value="$food->calorie ?? ''"
            :column="isset($food) && $food->ingredient_id !== null ? 'col-md-6 is_no_drinks d-none':'col-md-6 is_no_drinks'"
            :required="false"
        />

        <x-form-group
            name="serice"
            for="meal_period"
            isType="select"
            :column="isset($food) && $food->ingredient_id !== null ? 'col-md-6 is_no_drinks d-none':'col-md-6 is_no_drinks'"
            for="meal_periods[]"
            class="select2"
            multiple
            :required="false"
        >
            <option value="">Select serice</option>
            @foreach ($data->mealPeriods as $mealPeriod)
                <option value="{{ $mealPeriod->id }}"
                    @isset($food)
                        @foreach ($food->mealPeriods as $fMealPeriod)
                            @selected($fMealPeriod->id == $mealPeriod->id)
                        @endforeach
                    @endisset
                >{{ $mealPeriod->name }}</option>
            @endforeach
        </x-form-group>

        <x-form-group
            name="addons"
            for="addons[]"
            isType="select"
            :column="isset($food) && $food->ingredient_id !== null ? 'col-md-6 is_no_drinks d-none':'col-md-6 is_no_drinks'"
            :required="false"
            class="select2"
            multiple
        >
            <option value="">Select meal period</option>
            @foreach ($data->addons as $addon)
                <option value="{{ $addon->id }}"
                    @isset($food)
                        @foreach ($food->addons as $faddon)
                            @selected($faddon->id == $addon->id)
                        @endforeach
                    @endisset
                >{{ $addon->name }}</option>
            @endforeach
        </x-form-group>

        <x-form-group
            name="allergies"
            for="allergies[]"
            :column="isset($food) && $food->ingredient_id !== null ? 'col-md-6 is_no_drinks d-none':'col-md-6 is_no_drinks'"
            isType="select"
            multiple
            class="select2"
            :required="false"
        >
            <option value="">Select meal period</option>
            @foreach($data->allergies as $allergy)
                <option value="{{ $allergy->id }}"
                    @isset($food)
                        @foreach ($food->allergies as $fallergy)
                            @selected($fallergy->id == $allergy->id)
                        @endforeach
                    @endisset
                >{{ $allergy->name }}</option>
            @endforeach
        </x-form-group>

        <x-form-group name="image" question="recommended size 800*600" type="file" column="col-md-6" :required="false" />

        <x-form-group name="token" column="col-md-6" :required="false" :value="$food->token ?? null" placeholder="Enter token" />
        <x-form-group name="SKU" for="sku" column="col-md-6" :required="false" :value="$food->sku ?? null" placeholder="Enter sku" />
        <x-form-group name="weight" column="col-md-6" :required="false" :value="$food->weight ?? null" placeholder="Enter weight" />
        <x-form-group name="GTIN" for="gtin" column="col-md-6" :required="false" :value="$food->gtin ?? null" placeholder="Enter GTIN" />

        <div class="col-md-6 mb-3 align-self-center">
            <div class="d-flex">
                @foreach (['online_item_visibility','sellable'] as $item)
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" id="{{ $item }}" name="{{ $item }}" @checked(isset($food) ? $food->{$item} : true)>
                        <label class="form-check-label" for="{{ $item }}">
                            {{ ucfirst(str_replace('_', ' ', $item)) }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>


        <x-form-group name="description" isType="textarea" column="col-12" :required="false" placeholder="Write description">{{ $food->description ?? null }}</x-form-group>
    </div>

</x-form-modal>



{{-- @extends('layouts.app')

@section('title', isset($food) ? 'Update menu':'Add New menu')

@section('content')

    <div class="col-12 my-5">
        <x-page-card :title="isset($food) ? 'Update menu':'Add New menu'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('food.menu.index')" title="Back to food" icon="back" />
                @isset($food)
                    <x-card-heading-button :url="route('food.menu.show', $food->id)" title="Menu Details" icon="show" />
                @endisset
            </x-slot>

            <form id="fileForm" action="{{isset($food) ? route('food.menu.update', $food->id) : route('food.menu.store')}}" method="post" data-redirect="{{ route('food.menu.index') }}">
                @csrf
                @isset($food)
                    @method('PUT')
                @endisset

                <div class="card-body">


                </div>
                <div class="card-footer text-end p-4">
                    <x-submit-button :text="isset($food) ? 'Update':'Save'" />
                </div>
            </form>

        </x-page-card>
    </div>

@endsection

@push('js')
    <script>
        $(function() {
            $('.sizeSelect').select2({
                tags: true
            });
        });
    </script>
@endpush --}}
