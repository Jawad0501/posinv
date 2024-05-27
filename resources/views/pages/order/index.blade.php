@extends('layouts.app')

@section('title', 'Sales')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Sales List">

            <x-slot:cardButton>
                <div class="btn-group">
                    
                    <input type="hidden" name="status" id="status" value="{{ request()->has('type') ? request()->get('type'):'all'}}">
                </div>
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Invoice', 'Date', 'Customer Name', 'Status', 'Grand Total', 'Action']" />
            </div>
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: {
            url: '{!! route('orders.order.index') !!}',
            data: function (d) {
                d.status = $('input[name="status"]').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'invoice', name: 'invoice'},
            {data: 'date', name: 'date'},
            {data: 'user_name', name: 'user.first_name'},
            {data: 'status', name: 'status', render: function(data) {
                let bg = '';
                if(data == 'due') bg = 'bg-warning-800';
                else if(data == 'paid') bg = 'bg-success-800';
                return '<span class="badge '+bg+' w-50 text-capitalize">'+data+'</span>';
            }},
            {data: 'grand_total', name: 'grand_total', render: function(data) {
                return convertAmount(data);
            }},
            {data: 'action', searchable: false, orderable: false}
        ]
    });

    $(function() {
        $(document).on('click', '#filterData', function() {
            $('.btn-group .btn').removeClass('active');
            $(this).addClass('active');
            let type = $(this).data('type');
            $('input[name="status"]').val(type);
            table.ajax.reload();
        });
    });
</script>
@endpush
