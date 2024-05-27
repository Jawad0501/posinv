@extends('layouts.app')

@section('title', 'Purchase')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Purchase List">
            
            @can('create_purchase')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('purchase.create')" title="Add New Purchase" icon="add" />
                </x-slot>
            @endcan
            

            <div class="card-body">
                <x-table :items="['Sl No', 'Reference No', 'Date', 'Supplier', 'Grand Total', 'Due Amount', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />
    
    <script>
        var table = $('#table').DataTable({
            ajax: '{!! route('purchase.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'reference_no', name: 'reference_no'},
                {data: 'date', name: 'date'},
                {data: 'supplier.name', name: 'supplier.name'},
                {data: 'total_amount', name: 'total_amount', render: function(data) {
                    return convertAmount(data);
                }},
                {data: 'due_amount', name: 'due_amount', render: function(data) {
                    return convertAmount(data);
                }},
                {data: 'action', searchable: false, orderable: false}
                
            ]
        });
    </script>
@endpush
