@extends('layouts.app')

@section('title', 'Table Lists')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Table Lists">

            @can('create_table')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('table-layout.create')" title="Add new table" icon="add" :addId="true" />
                </x-slot>
            @endcan

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Table No', 'Capacity', 'Available', 'Image', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('table-layout.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'name', name: 'name'},
            {data: 'number', name: 'number'},
            {data: 'capacity', name: 'capacity'},
            {data: 'available', name: 'available'},
            {data: 'image', name: 'image', searchable: false, orderable: false, render: function(data, type, row, meta) {
                return `
                <div class="w-100 text-center">
                    <img src="${showUploadedFile(data)}" alt="${row.name}" width="90px" class="mx-auto" />
                </div>
                `;
            }},
            {data: 'action', searchable: false, orderable: false}
        ]
    });
</script>
@endpush
