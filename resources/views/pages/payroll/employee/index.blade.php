@extends('layouts.app')

@section('title', 'Employee')

@push('css')
    <x-datatable.style />
@endpush

@section('content')
    <div class="col-12 my-5">
        <x-page-card title="Employee List">
            <x-slot:cardButton>
                <x-card-heading-button :url="route('payroll.employee.create')" title="Add New employee" icon="add" :addId="true" />
            </x-slot>
            <div class="card-body"></div>
        </x-page-card>
    </div>
@endsection

@push('js')
    <x-datatable.script :server="false" />
    <script>
        $(document).ready(function () {
            function getPayrollData(url = '{!! route('payroll.employee.index') !!}') {
                $.ajax({
                    type: 'GET',
                    url: url,
                    dataType: 'HTML',
                    success: function (response) {
                        $('.card-body').html(response);
                        let is_datatable = $('.card-body input#is_datatable').val();
                        if(is_datatable) {
                            $('#table').DataTable();
                        }
                    },
                    error: function(err) {
                        handleError(err);
                    }
                });
            }

            $(document).on('click', '#getData', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                if(url != '#') {
                    getPayrollData($(this).attr('href'));
                }
            });
            window.getPayrollData = getPayrollData;
            getPayrollData();
        });
    </script>
@endpush
