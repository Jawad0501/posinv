@extends('layouts.app')

@section('title', 'Bank Transaction')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Bank Transaction List">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('bank-transaction.create')" title="Add New Transaction" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Bank', 'Date', 'Type', 'Withdraw/Deposite ID', 'Amount', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('bank-transaction.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'bank.name', name: 'bank.name'},
            {data: 'date', name: 'date'},
            {data: 'type', name: 'type'},
            {data: 'withdraw_deposite_id', name: 'withdraw_deposite_id'},
            {data: 'amount', name: 'amount', render: function(data) {
                return convertAmount(data);
            }},
            {data: 'action', searchable: false, orderable: false}
        ]
    });
</script>
@endpush
