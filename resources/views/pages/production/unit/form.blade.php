@extends('layouts.app')

@section('title', isset($productionUnit) ? 'Update Menu Cost':'Menu Cost')

@section('content')

    <div class="col-12 my-5">

        <x-page-card :title="isset($productionUnit) ? 'Update Menu Cost':'Menu Cost'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('production-unit.index')" title="Back to Production" icon="back" />
                @isset($productionUnit)
                    <x-card-heading-button :url="route('production-unit.show', $productionUnit->id)" title="Show Details" icon="show" />
                @endisset
            </x-slot>

            <form id="form" action="{{isset($productionUnit) ? route('production-unit.update', $productionUnit->id) : route('production-unit.store')}}" method="post" data-redirect="{{ route('production-unit.index') }}">
                @csrf
                @isset($productionUnit)
                    @method('PUT')
                @endisset

                <div class="card-body">

                    <div class="row">
                        @php
                            $variants = null;
                        @endphp
                        <x-form-group
                            name="food_name"
                            isType="select"
                            column="col-md-6"
                            class="select2"
                        >
                            <option value="">Select food name</option>
                            @foreach ($data['foods'] as $food)
                                <option value="{{ $food->id }}"
                                    @isset($productionUnit)
                                        @selected($productionUnit->food_id == $food->id)
                                        @php
                                            if ($productionUnit->food_id == $food->id) {
                                                $variants = $food->variants;
                                            }
                                        @endphp
                                    @endisset
                                >
                                    {{ $food->name }}
                                </option>
                            @endforeach
                        </x-form-group>

                        <x-form-group
                            name="variant_name"
                            isType="select"
                            column="col-md-6"
                            class="select2"
                        >
                            @isset($productionUnit)
                                @foreach ($variants as $variant)
                                    <option value="">Select variant name</option>
                                    <option value="{{ $variant->id }}" @selected($productionUnit->variant_id == $variant->id)>
                                        {{ $variant->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </x-form-group>

                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="mb-3 text-end">
                                <button type="button" id="addMoreItem" class="btn btn-primary text-white">Add more item</button>
                            </div>
                            <table class="table table-bordered text-center" id="unit-items">
                                <tbody>
                                    <tr>
                                        <th width="10%">SL</th>
                                        <th width="20%">Item Information <span class="text-primary-800">*</span></th>
                                        <th width="20%">Qty <span class="text-primary-800">*</span></th>
                                        <th width="20%">Unit <span class="text-primary-800">*</span></th>
                                        <th width="20%">Price</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                    @isset($productionUnit)
                                        @foreach ($productionUnit->items as $key => $item)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                    <input type="hidden" name="items[]" value="{{ $item->id }}" />
                                                </td>
                                                <td>
                                                    <select name="products[]" id="product_id_{{ $key }}" class="form-control select2" required>
                                                        <option value="">Select option</option>
                                                        @foreach ($data['ingredients'] as $ingredient)
                                                            @if ($ingredient->purchase_items_count > 0)
                                                            <option
                                                                value="{{ $ingredient->id.'_'.$ingredient->unit->slug.'_'.$ingredient->purchaseItems[0]?->unit_price }}"
                                                                @selected($ingredient->id == $item->ingredient_id)
                                                            >
                                                                {{ $ingredient->name }} ({{ $ingredient->unit->name }})
                                                            </option>
                                                            @endif

                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="qunatity[]" id="qunatity" min="1" class="form-control text-end" value="{{ $item->quantity }}" required placeholder="0.00" autocomplete="off" />
                                                </td>
                                                <td>
                                                    @php
                                                        $units = [];
                                                        if(in_array($item->unit, ['kg', 'gm'])) $units = ['kg', 'gm'];
                                                        elseif(in_array($item->unit, ['ltr', 'ml'])) $units = ['ltr', 'ml'];
                                                        else if($item->unit == 'pcs') $units = ['pcs'];
                                                    @endphp
                                                    <select name="units[]" id="unit_id" class="form-control" required>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit }}" @selected($unit == $item->unit)>{{ $unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="price[]" id="price" value="{{ $item->price }}" class="form-control text-end" placeholder="0.00" readonly />
                                                </td>
                                                <td>
                                                    <a href="{{ route('production.item.destroy', $item->id) }}" id="removeUnitItem" class="btn btn-warning">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>
                                                1
                                                <input type="hidden" name="items[]" id="items" value="0" />
                                            </td>
                                            <td>
                                                <select name="products[]" id="product_id_1" class="form-control select2" required>
                                                    <option value="">Select option</option>
                                                    @foreach ($data['ingredients'] as $ingredient)
                                                        @if ($ingredient->purchase_items_count > 0)
                                                            <option value="{{ $ingredient->id.'_'.$ingredient->unit->slug.'_'.$ingredient->purchaseItems[0]->unit_price }}">
                                                                {{ $ingredient->name }} ({{ $ingredient->unit->name }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="qunatity[]" id="qunatity" min="1" class="form-control text-end" value="1" required placeholder="0.00" autocomplete="off" />
                                            </td>
                                            <td>
                                                <select name="units[]" id="unit_id" class="form-control" required></select>
                                            </td>
                                            <td>
                                                <input name="price[]" id="price" class="form-control text-end" placeholder="0.00" readonly />
                                            </td>
                                            <td>
                                                <a href="#" id="removeUnitItem" class="btn btn-warning">Delete</a>
                                            </td>
                                        </tr>
                                    @endisset

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end">Total:</td>
                                        <td id="grand_total">{{ isset($productionUnit) ? convert_amount($productionUnit->items()->sum('price')) : convert_amount(0) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <x-submit-button :text="isset($productionUnit) ? 'Update':'Submit'" />
                </div>
            </form>
        </x-page-card>
    </div>
@endsection
