@extends('layouts.app')

@section('title', isset($production) ? 'Update Production':'Add Production')

@section('content')

    <div class="col-12 my-5">

        <x-page-card :title="isset($production) ? 'Update Production':'Add Production'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('production.index')" title="Back to Production" icon="back" />
                @isset($production)
                    @can('show_production')
                        <x-card-heading-button :url="route('production.show', $production->id)" title="Show Details" icon="show" />
                    @endcan
                @endisset
            </x-slot>

            <form id="form" action="{{isset($production) ? route('production.update', $production->id) : route('production.store')}}" method="post" data-redirect="{{ route('production.index') }}">
                @csrf
                @isset($production)
                    @method('PUT')
                @endisset

                <div class="card-body">

                    <div class="row">
                        <x-form-group
                            name="production_unit"
                            isType="select"
                            column="col-md-6"
                            class="select2"
                        >
                            <option value="">Select food name</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    @isset($production)
                                        @selected($production->food_id == $unit->id)
                                    @endisset
                                >
                                    {{ $unit->food->name }} ({{ $unit->variant->name }})
                                </option>
                            @endforeach
                        </x-form-group>

                        <x-form-group
                            name="production_date"
                            column="col-md-6"
                            class="datepicker"
                            placeholder="Enter production date"
                        />
                        <x-form-group
                            name="serving_unit"
                            type="number"
                            column="col-md-6"
                            placeholder="Enter serving unit"
                        />
                        <x-form-group
                            name="expire_date"
                            column="col-md-6"
                            class="datepicker"
                            placeholder="Enter expire date"
                        />

                    </div>

                </div>
                <div class="card-footer">
                    <x-submit-button :text="isset($production) ? 'Update':'Submit'" />
                </div>
            </form>
        </x-page-card>
    </div>
@endsection
