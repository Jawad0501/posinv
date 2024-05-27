@extends('layouts.app')

@section('title', 'Page')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Page">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('frontend.page.create')" title="Add New page" icon="add" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['SN', 'Name', 'Title', 'Status', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('frontend.page.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'name', name: 'name'},
            {data: 'title', name: 'title'},
            {data: 'status', name: 'status', render: function(data) {
                return data ? 'Active': 'Disabled';
            }},
            {data: 'action', searchable: false, orderable: false}
        ]
    });

    
</script>
@endpush
