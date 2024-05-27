@extends('layouts.app')

@section('title', 'Attendance')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Filter Data">

            <div class="card-body">
                
                <form action="{{ route('attendance.log') }}" method="GET">
                    <div class="row">
                        
                        <div class="col-md-3">
                            <x-form-group name="staff" :isType="'select'">
                                <option value="">Select staff</option>
                                @foreach ($staff as $data)
                                    <option value="{{ $data->id }}" @if(request()->get('staff')) @selected($data->id == request()->get('staff') ? true : false) @endif>{{ $data->name }}</option>
                                @endforeach
                            </x-form-group>
                        </div>

                        <div class="col-md-3">
                            <x-form-group name="from_date" class="datepicker" :value="request()->get('from_date') ?? ''" />
                        </div>
                        
                        <div class="col-md-3">
                            <x-form-group name="to_date" class="datepicker" :value="request()->get('to_date') ?? ''" />
                        </div>
                        
                        <div class="col-md-3">
                            <x-submit-button text="Search" :isReport="true" />
                        </div>

                    </div>
                </form>

            </div>

        </x-page-card>
        <div class="my-4"></div>
        
        <x-page-card title="Attendance Log">

            <x-slot:cardButton>
                <a href="#" class="btn btn-primary text-white" onclick="printDiv()">
                    <i class="fa-solid fa-print"></i>
                </a>
            </x-slot>

            <div class="card-body" id="printArea">
                
                @if (request()->get('staff')) 
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th colspan="6" class="text-center">
                                    {{ $attendances[0]->staff->name ?? '' }} Attendance History
                                    @if (!empty(request()->get('from_date')) && !empty(request()->get('to_date')))
                                        From {{ format_date(request()->get('from_date')) }} To {{ format_date(request()->get('to_date')) }}
                                    @endif
                                </th>
                            </tr>
                            
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Worked Time</th>
                            </tr>

                            @forelse ($attendances as $index => $attendance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ format_date($attendance->date) }}</td>
                                    <td>{{ $attendance->check_in }}</td>
                                    <td>{{ $attendance->check_out }}</td>
                                    <td>{{ $attendance->stay }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">Records not available</td>
                                </tr>
                            @endforelse
                            <tr>
                                <th colspan="4" class="text-end">Working Hours Summary:</th>
                                <th>{{ $total_working_time }}</th>
                            </tr>
                        </tbody>
                    </table>
                @else
                    @foreach ($attendances as $key => $attendance)
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="6" class="text-center">Attendance History of {{ format_date($key) }}</th>
                                </tr>
                                <tr>
                                    <th>SL</th>
                                    <th>Employee Name</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Worked Time</th>
                                </tr>
                                @foreach ($attendance as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->staff->name }}</td>
                                        <td>{{ $item->check_in }}</td>
                                        <td>{{ $item->check_out }}</td>
                                        <td>{{ $item->stay }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                @endif
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
            {data: 'DT_RowIndex', searchable: false, orderable: false},
            {data: 'staff.name', name: 'staff.name'},
            {data: 'date', name: 'date'},
            {data: 'check_in', name: 'check_in'},
            {data: 'check_out', name: 'check_out'},
            {data: 'stay', name: 'stay'},
            {data: 'action', searchable: false, orderable: false, render: function(data, type, row, meta) {
                if(row.check_out != null) return 'Checked out';
                else {
                    return `<a href="${route('attendance.edit', { attendance: row.id})}" class="btn primary-btn" id="editBtn"><i class="icofont-wall-clock me-1"></i>Check out</a>`;
                }
            }}

        ]
    });

    function printDiv() {
        var printContents = document.getElementById('printArea').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }

</script>
@endpush
