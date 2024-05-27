@extends('layouts.app')

@section('title', 'Review')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Review List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('orders.review.create')" title="Add New review" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Food', 'Customer Name', 'Rating', 'Status', 'Action']" />
            </div>
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('orders.review.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'food.name', name: 'food.name'},
            {data: 'user.first_name', name: 'user.first_name', render: function(data, type, row) {
                return row.user.first_name+' '+row.user.last_name;
            }},
            {data: 'rating', name: 'rating'},
            {data: 'status', name: 'status', render: function(data) {
                return data ? 'Active': 'Disabled';
            }},
            {data: 'action', searchable: false, orderable: false}
        ]
    });


</script>
@endpush
