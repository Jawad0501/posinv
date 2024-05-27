@extends('layouts.app')

@section('title', 'Stock Adjustment')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Stock Adjustments">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('stock-adjustment.create')" title="Add New Stock Adjustment" icon="add" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Reference No', 'Date', 'Ingredient Count', 'Responsible Person', 'Added By', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('stock-adjustment.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'reference_no', name: 'reference_no'},
            {data: 'date', name: 'date'},
            {data: 'items_count', searchable: false},
            {data: 'staff.name', name: 'staff.name'},
            {data: 'added_by', name: 'added_by'},
            {data: 'action', searchable: false, orderable: false}
        ]
    });

    
</script>
@endpush
