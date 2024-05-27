@extends('layouts.app')

@section('title', 'Product Returns')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Product Return List">
            <!-- Assign and change permission from create_supplier -->
            @can('create_supplier')  
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('returns.return.create')" title="Add New Product Return" icon="add"/>
                    <!-- <x-card-heading-button :url="route('supplier.upload')" title="Upload supplier" icon="upload" :addId="true" class="dark-bg" /> -->
                </x-slot>   
            @endcan
            

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Contact Type', 'Invoice No', 'Return Total', 'Date', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('returns.return.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'person', name: 'person'},
            {data: 'product_return_person', name: 'contact_type'},
            {data: 'order_invoice_no', name: 'order_invoice_no'},
            {data: 'grand_total', name: 'return_amount'},
            {data: 'return_date', name: 'return_date'},
            {data: 'action', searchable: false, orderable: false}
        ],
    });

    
</script>
@endpush
