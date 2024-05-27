@extends('layouts.app')

@section('title', 'Menu Allergy')

@push('css')
    <x-datatable.style/>
@endpush

@section('content')

    <div class="col-12 col-md-12 col-lg-12 my-5">

        <x-page-card title="Menu Allergy">
            
            @can('create_allergy')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('food.allergy.create')" title="Add new allergy" icon="add" :addId="true" />
                </x-slot>
            @endcan

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Icon', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script/>

    <script>

        var table = $('#table').DataTable({
            ajax: '{!! route('food.allergy.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'name', name: 'name'},
                {data: 'image', name: 'image', searchable: false, orderable: false, render: function(data, type, row, meta) {
                    return `<img src="${showUploadedFile(data)}" alt="${row.name}" width="70px" />`;
                }},
                {data: 'action', searchable: false, orderable: false}
                
            ]
        });
    </script>
@endpush

