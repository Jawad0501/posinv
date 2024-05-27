@extends('layouts.app')

@section('title', isset($waste) ? 'Update waste':'Add New waste')

@section('content')

    <div class="col-12 my-5">

        <x-page-card :title="isset($waste) ? 'Update waste':'Add New waste'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('waste.index')" title="Back to waste" icon="back" />
                @isset($waste)
                    <x-card-heading-button :url="route('waste.show', $waste->id)" title="Waste Details" icon="show" />
                @endisset
            </x-slot>

            <form id="form" action="{{isset($waste) ? route('waste.update', $waste->id) : route('waste.store')}}" method="post" data-redirect="{{ route('waste.index') }}">
                @csrf
                @isset($waste)
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
                                <option value="{{ $person->id }}" @isset($waste) @selected($waste->staff_id == $person->id ? true:false) @endisset>{{ $person->name }}</option>
                            @endforeach
                        </x-form-group>

                        <x-form-group
                            name="date"
                            :value="$waste->date ?? date('Y-m-d')"
                            class="datepicker"
                            column="col-md-4"
                        />
                        <x-form-group
                            name="food"
                            :isInput="false"
                            :required="false"
                            column="col-md-4"
                            class="select2"
                            data-role="stock-adjusment"
                        >
                            <option value="">Select food</option>
                            @foreach ($foods as $food)
                                <option value="{{ $food->id }}">{{ $food->name }}</option>
                            @endforeach
                        </x-form-group>

                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Food Name</th>
                                        <th>Quantity/Amount</th>
                                        <th>Loss Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="items">
                                    @isset($waste)
                                        @foreach (json_decode($waste->items, true) as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td>
                                                    {{ $item['food_name'] }}
                                                    <input type="hidden" name="food_id[]" id="food_id" value="{{ $item['food_id'] }}" />
                                                    <input type="hidden" name="food_name[]" id="food_name" value="{{ $item['food_name'] }}" />
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="quantity[]" id="quantity" value="{{ $item['quantity'] }}" placeholder="Quantity" />
                                                    <input type="hidden" name="price[]" id="price" value="{{ $item['price'] }}" />
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="total[]" id="total" value="{{ $item['total'] }}" readonly />
                                                </td>
                                                <td>
                                                    <a href="#" class="btn primary-text" id="removeFoodItem"><i class='icofont-ui-delete'></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2">
                                            <x-form-group name="total_loss" id="total_loss" :readonly="true" :value="$waste->total_loss ?? 0.00" />
                                        </td>
                                    </tr>
                                </tfoot>
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
                            placeholder="Enter..."
                        >
                            {{ $waste->note ?? '' }}
                        </x-form-group>
                    </div>

                </div>

                <div class="card-footer text-end">
                    <x-submit-button :text="isset($waste) ? 'Update':'Save'" />
                </div>


            </form>

        </x-page-card>
    </div>

@endsection
