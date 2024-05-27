@extends('layouts.app')

@section('title', 'Production')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Production">
            
            @can('create_production')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('production.create')" title="Add New Production" icon="add" />
                </x-slot>
            @endcan

            <div class="card-body">
                <x-table :items="['Sl No', 'Food Name', 'Variant Name', 'Date', 'Serving Unit', 'Expire Date', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />
    
    <script>
        var table = $('#table').DataTable({
            ajax: '{!! route('production.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'unit.food.food_name', name: 'unit.food.food_name'},
                {data: 'unit.variant.name', name: 'unit.variant.name'},
                {data: 'production_date', name: 'production_date'},
                {data: 'serving_unit', name: 'serving_unit'},
                {data: 'expire_date', name: 'expire_date'},
                {data: 'action', searchable: false, orderable: false}
            ]
        });
    </script>
@endpush
