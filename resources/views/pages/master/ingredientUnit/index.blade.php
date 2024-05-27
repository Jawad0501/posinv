@extends('layouts.app')

@section('title', 'Product Unit')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 col-md-8 offset-md-2 my-5">
        <x-page-card title="Product Unit List">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('ingredient-unit.create')" title="Add New product unit" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Description', 'Status', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('ingredient-unit.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'status', name: 'status', render: function(data) {
                return data ? 'Active': 'Disabled';
            }},
            {data: 'action', searchable: false, orderable: false}
            
        ]
    });

    
</script>
@endpush
