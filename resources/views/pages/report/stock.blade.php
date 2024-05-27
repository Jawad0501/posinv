@extends('layouts.app')

@section('title', 'Stock Report')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">

        <x-page-card title="Filter Data">

            <div class="card-body">

                <form action="/" method="GET" id="filterData">
                    <div class="row">

                        <x-form-group name="category" isType="select" class="select2" :islabel="false" column="col-md-4">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-form-group>
                        <x-form-group name="ingredient" for="ingredient_id" isType="select" class="select2" :islabel="false" column="col-md-4">
                            <option value="">Select ingredient</option>
                            @foreach ($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
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

        <x-page-card title="Stock Report">

            <x-slot:cardButton>
                <x-card-heading-button title="Reload" icon="reload" id="reloadData" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Ingredient(Code)', 'Category', 'Stock Qty/Amount', 'Alert Qty/Amount']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />
@endpush
