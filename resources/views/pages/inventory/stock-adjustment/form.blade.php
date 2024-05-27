@extends('layouts.app')

@section('title', isset($stockAdjustment) ? 'Update Stock Adjustment':'Add New Stock Adjustment')

@section('content')

    <div class="col-12 my-5">

        <x-page-card :title="isset($stockAdjustment) ? 'Update Stock Adjustment':'Add New Stock Adjustment'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('stock-adjustment.index')" title="Back to stock adjustment" icon="back" />
                @isset($stockAdjustment)
                    <x-card-heading-button :url="route('stock-adjustment.show', $stockAdjustment->id)" title="Show Details" icon="show" />
                @endisset
            </x-slot>

            <form id="form" action="{{isset($stockAdjustment) ? route('stock-adjustment.update', $stockAdjustment->id) : route('stock-adjustment.store')}}" method="post" data-redirect="{{ route('stock-adjustment.index') }}">
                @csrf
                @isset($stockAdjustment)
                    @method('PUT')
                @endisset

                <div class="card-body">

                    <div class="row">

                        <x-form-group
                            name="Responsible Person"
                            for="person"
                            isType="select"
                            class="select2"
                            column="col-md-4"
                        >
                            <option value="">Select person</option>
                            @foreach ($persons as $person)
                                <option value="{{ $person->id }}" @isset($stockAdjustment) @selected($stockAdjustment->staff_id == $person->id ? true:false) @endisset>{{ $person->name }}</option>
                            @endforeach
                        </x-form-group>

                        <x-form-group
                            name="date"
                            :value="$stockAdjustment->date ?? date('Y-m-d')"
                            class="datepicker"
                            column="col-md-4"
                        />

                        <x-form-group
                            name="ingredient"
                            isType="select"
                            :required="false"
                            column="col-md-4"
                            class="select2"
                            data-role="stock-adjusment"
                        >
                            <option value="">Select ingredient</option>
                            @foreach ($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" @isset($stockAdjustment) @selected($stockAdjustment->ingredient_id == $ingredient->id ? true:false) @endisset>{{ $ingredient->name }}({{ $ingredient->code }})</option>
                            @endforeach
                        </x-form-group>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Ingredient(Code)</th>
                                        <th>Quantity/Amount</th>
                                        <th>Consumption Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="items">
                                    @isset($stockAdjustment)
                                        @foreach ($stockAdjustment->items as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td>
                                                    {{ $item->ingredient->name }} ({{ $item->ingredient->code }})
                                                    <input type="hidden" name="ingredient_id[]" id="ingredient_id" value="{{ $item->ingredient->id }}" />
                                                </td>
                                                <td class="d-flex justify-content-start align-items-center" id="quantity">
                                                    <input type="text" class="form-control" name="quantity_amount[]" id="quantity_amount" value="{{ $item->quantity_amount }}" placeholder="Unit Price" style="width:80%" />
                                                    <span class="ms-2">{{ $item->ingredient->unit->name }}</span>
                                                </td>
                                                <td>
                                                    <select name="consumption_status[]" id="consumption_status" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="plus" @selected($item->consumption_status == 'plus' ? true : false)>Plus</option>
                                                        <option value="minus" @selected($item->consumption_status == 'minus' ? true : false)>Minus</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <a href="{{ route('stock-adjustment.item.destroy', $item->id) }}" class="btn primary-text" id="removeIngredientItem"><i class='icofont-ui-delete'></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <x-form-group
                            name="note"
                            isType="textarea"
                            :required="false"
                            column="col-6"
                            rows="2"
                        >
                            {{ $stockAdjustment->note ?? '' }}
                        </x-form-group>
                    </div>

                </div>

                <div class="card-footer text-end">
                    <x-submit-button :text="isset($stockAdjustment) ? 'Update':'Save'" />
                </div>


            </form>

        </x-page-card>
    </div>

@endsection
