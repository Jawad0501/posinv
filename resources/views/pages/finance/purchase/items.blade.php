@extends('layouts.app')

@section('title', 'Expired purchase items list')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Expired purchase items list">
            <div class="card-body">
                <x-table :items="['Sl No', 'Ingredient(Code)', 'Expire Date', 'Unit Price', 'Quantity/Amount', 'Total']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />
    
    <script>
        var table = $('#table').DataTable({
            ajax: '{!! route('purchase.items') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'ingredient.name', name: 'ingredient.name'},
                {data: 'expire_date', name: 'expire_date'},
                {data: 'unit_price', name: 'unit_price', render: function(data) {
                    return convertAmount(data);
                }},
                {data: 'quantity_amount', name: 'quantity_amount'},
                {data: 'total', name: 'total', render: function(data) {
                    return convertAmount(data);
                }}
            ]
        });
    </script>
@endpush
