@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')

    <div class="col-12 col-lg-12  my-5">
        <x-page-card title="Supplier List">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('supplier.index')" title="Back to supplier" icon="back" />
            </x-slot>

            <div class="card-body mt-3">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $supplier->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $supplier->phone }}</td>
                        </tr>
                        <tr>
                            <th>Reference</th>
                            <td>{{ $supplier->reference }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $supplier->address }}</td>
                        </tr>
                        <tr>
                            <th>Driving license/Passport frontside</th>
                            <td>
                                <img src="{{ uploaded_file($supplier->id_card_front) }}" alt="{{ $supplier->name }}" class="img-fluid">
                            </td>
                        </tr>
                        <tr>
                            <th>Driving license/Passport backside</th>
                            <td>
                                <img src="{{ uploaded_file($supplier->id_card_back) }}" alt="{{ $supplier->name }}" class="img-fluid">
                            </td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>{{ $supplier->status ? 'Active': 'Disabled' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </x-page-card>

        <x-page-card title="Ledger" class="mt-4">
            <div class="card-body">
                <x-table :items="['Sl No', 'Date', 'Payment Method', 'Total Amount', 'Paid Amount', 'Due Amount', 'Action']">
                    <x-slot name="tfoot">
                        <th colspan="3" class="text-end">Total:</th>
                        <th>{{ $purchases->sum('total_amount') }}</th>
                        <th>{{ $purchases->sum('paid_amount') }}</th>
                        <th>{{ $purchases->sum('total_amount') - $purchases->sum('paid_amount')}}</th>
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
        ajax: '{!! route('supplier.show', $supplier->id) !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'date', name: 'date'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'paid_amount', name: 'paid_amount'},
            {data: 'due_amount', name: 'due_amount', render: function(data, type, row) {
                return due = row.total_amount - row.paid_amount;
            }},
            {data: 'action', searchable: false, orderable: false}
        ],
    });

    
</script>
@endpush