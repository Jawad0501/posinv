@extends('layouts.app')

@section('title', 'Coupon')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Coupon List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('frontend.coupon.create')" title="Add New Coupon" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Code', 'Discount Type', 'Discount', 'Expire Date', 'Status', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('frontend.coupon.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'code', name: 'code'},
            {data: 'discount_type', name: 'discount_type'},
            {data: 'discount', name: 'discount', render: function(data, type, row, meta) {
                return row.discount_type == 'Fixed' ? convertAmount(data) : data+'%';
            }},
            {data: 'expire_date', name: 'expire_date'},
            {data: 'status', name: 'status', render: function(data) {
                return data ? 'Active': 'Disabled';
            }},
            {data: 'action', searchable: false, orderable: false}
        ]
    });
</script>
@endpush
