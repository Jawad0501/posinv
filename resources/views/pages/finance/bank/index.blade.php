@extends('layouts.app')

@section('title', 'Bank')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Bank List">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('bank.create')" title="Add New Bank" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'AC Name', 'AC Number', 'Branch Name', 'Balance', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('bank.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'name', name: 'name'},
            {data: 'account_name', name: 'account_name'},
            {data: 'account_number', name: 'account_number'},
            {data: 'branch_name', name: 'branch_name'},
            {data: 'balance', name: 'balance', render: function(data) {
                return convertAmount(data);
            }},
            {data: 'action', searchable: false, orderable: false}
        ]
    });

    
</script>
@endpush
