@extends('layouts.app')

@section('title', 'Reservation')

@push('css')
    <x-datatable.style />
@endpush

@section('content')
    <div class="col-12 my-5">
        <x-page-card title="Reservation List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('orders.reservations.create')" title="Add New Reservation" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Phone', 'Email', 'Reservation Date', 'Expected Date', 'Person', 'Status', 'Action']" />
            </div>
        </x-page-card>
    </div>
@endsection

@push('js')
    <x-datatable.script />
    <script>
        var table = $('#table').DataTable({
            ajax: '{!! route('orders.reservations.index') !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'name', name: 'name'},
                {data: 'phone', name: 'phone'},
                {data: 'email', name: 'email'},
                {data: 'created_at', name: 'created_at'},
                {data: 'expected_date', name: 'expected_date'},
                {data: 'total_person', name: 'total_person'},
                {data: 'status', name: 'status', render: function(data) {
                    return '<span class="text-capitalize">'+data+'</span>';
                }},
                {data: 'action', searchable: false, orderable: false}
            ]
        });
    </script>
@endpush
