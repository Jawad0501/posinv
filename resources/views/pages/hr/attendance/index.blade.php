@extends('layouts.app')

@section('title', 'Clock In')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Clock In List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('attendance.create')" title="Add New attendance" icon="add" :addId="true" />
                @if (auth('staff')->user()->isAdmin())
                    <x-card-heading-button :url="route('attendance.upload')" title="Upload Bulk Attendance" icon="upload" :addId="true" class="dark-bg" />
                @endif
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Date', 'Check In', 'Check Out', 'Stay', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('attendance.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'staff.name', name: 'staff.name'},
            {data: 'date', name: 'date'},
            {data: 'check_in', name: 'check_in'},
            {data: 'check_out', name: 'check_out'},
            {data: 'stay', name: 'stay'},
            {data: 'action', searchable: false, orderable: false, render: function(data, type, row, meta) {
                if(row.check_out != null) return 'Checked out';
                else {
                    return `<a href="${route('attendance.edit', { attendance: row.id})}" class="btn btn-primary text-white" id="editBtn"><i class="fa-solid fa-clock me-1"></i>Check out</a>`;
                }
            }}
        ]
    });
</script>
@endpush
