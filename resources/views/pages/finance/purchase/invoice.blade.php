
@extends('layouts.app')

@section('title', 'Invoice No '. $purchase->invoice)

@push('css')
    <x-datatable.style />

    <style>
        *, *::before, *::after {
            box-sizing: content-box !important;
        }
    </style>
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
            <div class="d-flex justify-content-between align-items-center mb-4"  style=" width: 100%; max-width: 800px; margin: auto">
                <div class="">
                    <img src="{{uploaded_file(setting('invoice_logo'))}}" alt="" style="width: 100px">
                </div>
                <div class="text-end">
                    <div style="width:320px" class="fw-semibold text-black fs-4">{{setting('app_title')}}</div>
                    <div class="text-gray fw-semibold" style="font-size: 12px;">{{setting('address')}}</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4"  style=" width: 100%; max-width: 800px; margin: auto">
                <div>
                    <div class="fw-semibold fs-6 text-black">Invoice No. {{$purchase->reference_no}}</div>
                    <div class="fw-semibold" style="font-size: 14px;">Supplier Name: {{$purchase->supplier->name}}</div>
                    <div class="fw-semibold" style="font-size: 14px;">Supplier Phone: {{$purchase->supplier->phone}}</div>
                    <div class="fw-semibold" style="font-size: 14px;">Date: {{ date('d F Y', strtotime($purchase->created_at))}}</div>
                </div>
                <div class="text-end">
                    <span class="text-uppercase fw-medium text-black" style="font-size: 50px; letter-spacing: 3px;">Invoice</span>
                </div>
            </div>

            <div style="max-width: 800px; width: 100%; margin: auto">
                <div class="">
                    <div style="position: relative">
                        <div>
                            <table>
                                <thead style="background-color: #9E9E9E;">
                                    <td width="10%" style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">No.</td>
                                    <td width="40%" style=" padding: 5px 0px; text-transform: uppercase; color: white;">Name</td>
                                    <td width="15%" style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">QTY</td>
                                    <td width="15%" style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Unit Price</td>
                                    <td width="15%" style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Total</td>
                                </thead>

                                <tbody>
                                    @php
                                        $subtotal = $purchase->sum('total_amount');
                                    @endphp

                                    @foreach($purchase->items as $item)
                                        <tr>
                                            <td style="width: 50px; text-align:center; font-size: 12px; font-weight: 600; color: black">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td style="width: 270px; border-bottom: 2px solid #f2f2f3">
                                                <div style="font-size: 12px; font-weight: 600; color: black; margin-top: 10px">{{$item->ingredient?->name ? $item->ingredient?->name : ''}}</div>
                                            </td>
                                            <td style="width: 116px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{$item->quantity_amount}}
                                            </td>
                                            <td style="width: 118px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{$item->unit_price}}
                                            </td>
                                            <td style="width: 118px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{$item->total}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-5 pe-3">
                        <div class="d-flex justify-content-between" style="width:185px; margin-left:auto; border-bottom:  2px solid #f2f2f3; padding: 10px 0px">
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase;">Grand Total</div>
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 5px">{{convert_amount($subtotal)}}</div>
                        </div>
                        <div class="d-flex justify-content-between" style="width:185px; margin-left:auto; border-bottom:  2px solid #f2f2f3; padding: 10px 0px">
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase;">Paid</div>
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 5px">{{convert_amount($purchase->paid_amount)}}</div>
                        </div>
                        <div class="d-flex justify-content-between" style="width:185px; margin-left:auto; border-bottom:  2px solid #f2f2f3; padding: 10px 0px">
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase;">Due</div>
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 5px">{{convert_amount($purchase->due_amount)}}</div>
                        </div>
                        </div>
                        

                        <div style="text-transform: uppercase; text-align:end; font-size: 25px; font-weight: 600; color: black">
                            <div>Thank you</div>
                            <div>For your business</div>
                        </div>
                    </div>
                </div>
            </div>
            <x-card-heading-button :url="route('purchase.download-invoice', $purchase->id)" title="Download" icon="download" />

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
