@extends('layouts.app')

@section('title', 'Menu Cost')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Menu Cost">

            @can('create_production_unit')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('production-unit.create')" title="Add New Menu Cost" icon="add" />
                </x-slot>
            @endcan

            <div class="card-body">
                <x-table :items="['Sl No', 'Food Name', 'Variant Name', 'Price', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

    <script>
        var table = $('#table').DataTable({
            ajax: '{!! route('production-unit.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'food.name', name: 'food.name'},
                {data: 'variant.name', name: 'variant.name'},
                {data: 'items_sum_price', name: 'items_sum_price', render: function(data) {
                    return convertAmount(data);
                }},
                {data: 'action', searchable: false, orderable: false}
            ]
        });
    </script>
@endpush
