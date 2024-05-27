@extends('layouts.app')

@section('title', 'Role')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Role List">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('role.create')" title="Add new role" icon="add" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Description', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('role.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'action', searchable: false, orderable: false}
        ]
    });
</script>
@endpush
