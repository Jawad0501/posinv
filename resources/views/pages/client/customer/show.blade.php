@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')

    <div class="col-12 col-lg-12  my-5">
        <x-page-card title="Customer Details">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('customer.index')" title="Back to customer" icon="back" />
            </x-slot>

            <div class="card-body mt-3">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="20%">Name</th>
                            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $customer->delivery_address }}</td>
                        </tr>
                        <tr>
                            <th>Image</th>
                            <td>
                                <img src="{{ uploaded_file($customer->image) }}" alt="{{ $customer->first_name }}" class="img-fluid" >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </x-page-card>

        <x-page-card title="Ledger" class="mt-4">
            <div class="card-body">
                <x-table :items="['Sl No', 'Date', 'Invoice', 'Status', 'Total Amount', 'Action']">
                    <x-slot name="tfoot">
                        <th colspan="4" class="text-end">Total:</th>
                        <th>{{ $orders->sum('grand_total') }}</th>
                        <th></th>
                    </x-slot>
                </x-table>
            </div>
        </x-page-card>

    </div>

@endsection

@push('js')

<x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('client.customer.show', $customer->id) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'date', name: 'date'},
            {data: 'invoice', name: 'invoice'},
            {data: 'status', name: 'status'},
            {data: 'grand_total', name: 'grand_total'},
            {data: 'action', searchable: false, orderable: false}
        ],
    });

    
</script>
@endpush

