@extends('layouts.app')

@section('title', 'Employee Leave')

@push('css')
    <x-datatable.style />
@endpush

@section('content')
    <div class="col-12 my-5">
        <x-page-card title="Employee Leave List">
            <x-slot:cardButton>
                <x-card-heading-button :url="route('payroll.leave.create', ['employee'=> request()->get('employee')])" title="Add New Leave" icon="add" :addId="true" />
            </x-slot>
            <div class="card-body"></div>
        </x-page-card>
    </div>
@endsection

@push('js')
    <x-datatable.script :server="false" />
    <script>
        $(document).ready(function () {
            function getPayrollData() {
                $.ajax({
                    type: 'GET',
                    url: location.href,
                    dataType: 'HTML',
                    success: function (response) {
                        $('.card-body').html(response);
                        $('#table').DataTable();
                    },
                    error: function(err) {
                        handleError(err);
                    }
                });
            }
            window.getPayrollData = getPayrollData;
            getPayrollData();
        });
    </script>
@endpush
