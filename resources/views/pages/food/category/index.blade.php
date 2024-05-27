@extends('layouts.app')

@section('title', 'Menu Categories')

@push('css')
    <x-datatable.style/>
@endpush

@section('content')

    <div class="col-12 col-md-12 col-lg-12 my-5">

        <x-page-card title="Menu Categories">

            @can('show_category')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('food.category.create')" title="Add new category" icon="add" :addId="true" />
                </x-slot>
            @endcan
            

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Image', 'Is Drinks', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script/>

    <script>

        var table = $('#table').DataTable({
            ajax: '{!! route('food.category.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'name', name: 'name'},
                {data: 'image', name: 'image', searchable: false, orderable: false, render: function(data, type, row) {
                    return `<img src="${showUploadedFile(data)}" alt="${row.name}" width="70px" />`;
                }},
                {data: 'is_drinks', name: 'is_drinks', render: function(data) {
                    return data ? 'Yes' : 'No';
                }},
                {data: 'action', searchable: false, orderable: false}
            ]
        });
    </script>
@endpush
