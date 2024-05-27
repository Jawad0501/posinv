@extends('layouts.app')

@section('title', 'Product')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Product List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('ingredient.create')" title="Add New Product" :addId="true" icon="add" />
                <!-- <x-card-heading-button :url="route('ingredient.upload')" title="Upload Product" :addId="true" icon="upload" class="dark-bg" /> -->
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Category', 'Purchase QTY', 'Sale QTY', 'Alert Qty', 'Stock', 'Purchase Amount', 'Sale Amount', 'Status', 'Action']" />
            </div>

        </x-page-card>
    </div>



@endsection


@push('js')

    <x-datatable.script />

    <script>
        var table = $('#table').DataTable({
            ajax: {
                url: '{!! route('ingredient.index') !!}',
                data: function (d) {

                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'name', name: 'name'},
                {data: 'category.name', name: 'category.name'},
                {data: 'purchase_qty', name:'purchase_qty', searchable: false, orderable: false, render: function(data, type, row, meta) {
                    if (type === 'display' && data !== null) {
                        return parseInt(data);
                    }
                    return data;
                }},
                {data: 'sale_qty', name:'sale_qty', searchable: false, orderable: false, render: function(data, type, row, meta) {
                    if (type === 'display' && data !== null) {
                        return parseInt(data);
                    }
                    return data;
                }},
                {data: 'alert_qty', name: 'alert_qty'},
                {data: 'stock_qty', name: 'stock_qty', searchable: false, orderable: false, render: function(data, type, row, meta) {
                    if (type === 'display' && data !== null) {
                        return parseInt(data);
                    }
                    return data;
                }},
                {data: 'purchase_amount', name: 'purchase_amount', searchable: false, orderable: false, render: function(data, type, row, meta) {
                    if (type === 'display' && data !== null) {
                        return parseInt(data);
                    }
                    return data;
                }},
                {data: 'sale_amount', name: 'sale_amount', searchable: false, orderable: false, render: function(data, type, row, meta) {
                    if (type === 'display' && data !== null) {
                        return parseInt(data);
                    }
                    return data;
                }},
                {data: 'stock_qty', name: 'stock_qty', searchable: false, orderable: false, render: function(data, type, row, meta) {
                    if (data > 0) {
                        return 'Stock In Hand';
                    }
                    else{
                        return 'Stock Out';
                    }
                }},
                {data: 'action', searchable: false, orderable: false}
            ]
        });
    </script>
@endpush
