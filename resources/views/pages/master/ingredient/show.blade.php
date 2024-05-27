@extends('layouts.app')

@section('title', $ingredient->name)

@push('css')
    <x-datatable.style />
@endpush

@section('content')


    <div class="col-12 my-5">
        <x-page-card title="Product History">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('ingredient.index')" title="Back To Products" :addId="true" icon="back" />
            </x-slot>

            <div class="card-body">
                <div>
                    Product Name: {{$ingredient->name}} <br>
                    Purchase Quantity: {{$ingredient->purchase_qty}} <br>
                    Sell Quantity: {{$ingredient->sale_qty}} <br>
                    Stock in Hand: {{$ingredient->stock_qty}} <br>
                    Purchase Amount: {{$ingredient->purchase_amount}} <br>
                    Sale Amount: {{$ingredient->sale_amount}} <br>
                    Total Lots Purchased: {{$ingredient->total_purchase_items}} 
                </div>

                <div class="my-5">
                    <h3>Product History</h3>
                </div>
                <x-table :items="['Sl No', 'History', 'Quantity', 'Price', 'Status', 'Action']" />
            </div>

        </x-page-card>
    </div>



@endsection


@push('js')

    <x-datatable.script />

    <script>
        var table = $('#table').DataTable({
            "bSort": false,
            ajax: {
                url: '{!! route('ingredient.history', $ingredient->id) !!}',
                data: function (d) {

                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'instance', name: 'history'},
                {data: 'quantity', name: 'quantity'},
                {data: 'price', name: 'price'},
                {data: 'status', name: 'status'},
                {data: 'action', searchable: false, orderable: false}
            ]
        });
    </script>
@endpush
