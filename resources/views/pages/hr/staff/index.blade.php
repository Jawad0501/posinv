@extends('layouts.app')

@section('title', 'Staff')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Staff List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('staff.create')" title="Add New staff" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Image', 'Role', 'Name', 'ID', 'Email', 'Phone', 'IP address', 'Last Login', 'Status', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('staff.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'image', name: 'image', searchable: false, orderable: false, render: function(data, type, row) {
                return `<img src="${showUploadedFile(data)}" alt="${row.name}" width="70px" />`;
            }},
            {data: 'role.name', name: 'role.name'},
            {data: 'name', name: 'name'},
            {data: 'id_number', name: 'id_number'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'ip_address', name: 'ip_address'},
            {data: 'last_login', name: 'last_login'},
            {data: 'status', name: 'status', render: function(data) {
                return data ? 'Active': 'Disabled';
            }},
            {data: 'action', searchable: false, orderable: false}

        ]
    });


</script>
@endpush
