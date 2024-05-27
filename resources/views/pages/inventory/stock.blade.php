@extends('layouts.app')

@section('title', 'Stock')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Stock List">
            
            <div class="card-body">
                <x-table :items="['Sl No', 'Ingredient', 'Category', 'Stock Qty/Amount', 'Alert Qty/Amount']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

    <script>
        var table = $('#table').DataTable({
            ajax: '{!! route('stock.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'ingredient_name', name: 'ingredient.name'},
                {data: 'ingredient.category.name', name: 'ingredient.category.name'},
                {data: 'stock_qty', name: 'qty_amount'},
                {data: 'alert_qty', name: 'ingredient.alert_qty'},
            ]
        });
    </script>
@endpush
