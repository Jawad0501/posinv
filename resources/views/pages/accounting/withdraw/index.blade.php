@extends('layouts.app')

@section('title', 'Deposit')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Withdraw List">
            
            @can('create_purchase')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('withdraw.create')" title="Add New Withdraw" :addId="true" icon="add" />
                </x-slot>
            @endcan
            

            <div class="card-body">
                <div class="card py-3 px-4">
                    <div>Cash In Hand: {{ setting('cash_in_hand') }}</div>
                    <div>Today's Sale Income: </div>
                    <div>Today's Expense: </div>
                    <div>Total Withdraw: </div>
                </div>
                <x-table :items="['Sl No', 'Date', 'Amount', 'Note', 'Before Withdraw', 'After Withdraw']" />
                <x-slot name="tfoot">
                    <th colspan="2" class="text-end">Total:</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </x-slot>
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />
    
    <script>
        var table = $('#table').DataTable({
            ajax: '{!! route('withdraw.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'date', name: 'date'},
                {data: 'amount', name: 'amount'},
                {data: 'note', name: 'note'},
                {data: 'amount_before_withdraw', name: 'before_withdraw',},
                {data: 'amount_after_withdraw', name: 'after_withdraw'},
                
            ],
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();
                
                // Calculate the sum for each column
                var totalItemSum = api.column(2, {page: 'current'}).data().reduce(function (acc, curr) {
                    return acc + parseFloat(curr);
                }, 0);
                
                // Update the footer cells with the sums
                $(api.column(2).footer()).html(convertAmount(totalItemSum));
            }
        });
    </script>
@endpush
