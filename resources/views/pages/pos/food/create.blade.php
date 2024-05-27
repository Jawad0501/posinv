<x-form-modal
    title="Add new menu"
    action="{{ route('food.menu.store')}}"
    button="Submit"
    id="fileForm"
    size="xl"
>
    <div class="row">

        <x-form-group
            name="categories"
            isType="select"
            column="col-md-6"
            for="categories[]"
            class="select2"
            multiple
        >
            <option value="">Select Category</option>
            @foreach ($data['categories'] as $category)
                <option value="{{ $category->id }}"
                    @isset($food)
                        @foreach ($food->categories as $fCategory)
                            @selected($fCategory->id == $category->id)
                        @endforeach
                    @endisset
                >{{ $category->name }}</option>
            @endforeach
        </x-form-group>

        <x-form-group
            name="ingredient_name"
            isType="select"
            :required="false"
            column="col-md-6  is_drinks d-none"
            class="select2"
        >
            <option value="">Select category</option>
            @foreach ($data['ingredients'] as $ingredient)
                <option value="{{ $ingredient->id }}"
                    @isset($food)
                        @selected($food->ingredient_id == $ingredient->id)
                    @endisset
                >{{ $ingredient->name }}</option>
            @endforeach
        </x-form-group>

        <x-form-group
            name="name"
            placeholder="Enter food name"
            :value="$food->name ?? ''"
            column="col-md-6"
        />
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
            name="processing_time"
            placeholder="Enter food processing time"
            :value="isset($food) ? date('H:i', strtotime($food->processing_time)) : ''"
            column="col-md-6 is_no_drinks"
            class="clockpicker"
            autocomplete="off"
        />
        <x-form-group
            name="calorie"
            placeholder="Enter food calorie"
            :value="$food->calorie ?? ''"
            column="col-md-6 is_no_drinks"
        />

        <x-form-group
            name="meal_period"
            isType="select"
            for="meal_periods[]"
            column="col-md-6 is_no_drinks"
            class="select2"
            multiple
        >
            <option value="">Select meal period</option>
            @foreach ($data['mealPeriods'] as $mealPeriod)
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
            isType="select"
            for="addons[]"
            column="col-md-6 is_no_drinks"
            class="select2"
            multiple
        >
            <option value="">Select meal period</option>
            @foreach ($data['addons'] as $addon)
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
            isType="select"
            column="col-md-6 is_no_drinks"
            class="select2"
            multiple
        >
            <option value="">Select meal period</option>
            @foreach ($data['allergies'] as $allergy)
                <option value="{{ $allergy->id }}"
                    @isset($food)
                        @foreach ($food->allergies as $fallergy)
                            @selected($fallergy->id == $allergy->id)
                        @endforeach
                    @endisset
                >{{ $allergy->name }}</option>
            @endforeach
        </x-form-group>

        <x-form-group name="image" type="file" column="col-md-6" />
        <x-form-group name="description" isType="textarea" column="col-12" class="summernote" >{{ $food->description ?? '' }}</x-form-group>
    </div>
</x-form-modal>
