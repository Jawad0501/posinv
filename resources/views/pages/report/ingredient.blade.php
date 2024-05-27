@extends('layouts.app')

@section('title', 'Ingredient Report')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">

        <x-page-card title="Filter Data">

            <div class="card-body">

                <form action="/" method="GET" id="filterData">
                    <div class="row">
                        <x-form-group name="category" isType="select" column="col-md-4" class="select2" :islabel="false">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-form-group>
                        <x-form-group name="unit" isType="select" column="col-md-4" class="select2" :islabel="false">
                            <option value="">Select unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </x-form-group>

                        <div class="col-md-4">
                            <x-submit-button text="Search" :isReport="true" />
                        </div>
                    </div>
                </form>

            </div>

        </x-page-card>

        <div class="my-4"></div>

        <x-page-card title="Ingredient Report">

            <x-slot:cardButton>
                <x-card-heading-button title="Reload" icon="reload" id="reloadData" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Code', 'Name', 'Category', 'Purchase Price', 'Alert Qty', 'Unit']">
                    <x-slot name="tfoot">
                        <th colspan="4" class="text-end">Total:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </x-slot>
                </x-table>
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />
@endpush
