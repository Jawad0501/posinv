@extends('layouts.app')

@section('title', 'Supplier')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Supplier List">
            
            @can('create_supplier')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('supplier.create')" title="Add New supplier" icon="add" :addId="true" />
                    <x-card-heading-button :url="route('supplier.upload')" title="Upload supplier" icon="upload" :addId="true" class="dark-bg" />
                </x-slot>   
            @endcan
            

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Email', 'Phone', 'Status', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('supplier.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'status', name: 'status'},
            {data: 'action', searchable: false, orderable: false}
        ],
    });

    
</script>
@endpush
