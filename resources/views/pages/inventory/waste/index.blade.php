@extends('layouts.app')

@section('title', 'Waste')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Waste">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('waste.create')" title="Add New waste" icon="add" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['SN', 'Reference No', 'Date', 'Total Loss', 'Responsible Person', 'Note', 'Added By', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('waste.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'reference_no', name: 'reference_no'},
            {data: 'date', name: 'date'},
            {data: 'total_loss', name: 'total_loss'},
            {data: 'staff.name', name: 'staff.name'},
            {data: 'note', name: 'note'},
            {data: 'added_by', name: 'added_by'},
            {data: 'action', searchable: false, orderable: false}
        ]
    });

    
</script>
@endpush
