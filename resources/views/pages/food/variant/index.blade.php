@extends('layouts.app')

@section('title', 'Menu Variants')

@push('css')
    <x-datatable.style/>
@endpush

@section('content')

    <div class="col-12 col-md-12 col-lg-12 my-5">

        <x-page-card title="Menu Variants">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('food.variant.create')" title="Add new variant" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Menu Name', 'Name', 'Price', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script/>

    <script>

        var table = $('#table').DataTable({
            ajax: '{!! route('food.variant.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'food.name', name: 'food.name'},
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price', render: function(data) {
                    return convertAmount(data);
                }},
                {data: 'action', searchable: false, orderable: false}
            ]
        });
    </script>
@endpush
