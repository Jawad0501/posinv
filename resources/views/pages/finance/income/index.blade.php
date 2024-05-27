@extends('layouts.app')

@section('title', 'Income')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Income List">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('income.create')" title="Add New income" icon="add" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Category', 'Responsible Person', 'Amount', 'Date', 'Note', 'Status', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('income.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'category.name', name: 'category.name'},
            {data: 'staff.name', name: 'staff.name'},
            {data: 'amount', name: 'amount', render: function(data) {
                return convertAmount(data);
            }},
            {data: 'date', name: 'date'},
            {data: 'note', name: 'note'},
            {data: 'status', name: 'status', render: function(data) {
                return data ? 'Active': 'Disabled';
            }},
            {data: 'action', searchable: false, orderable: false}
        ]
    });

    
</script>
@endpush
