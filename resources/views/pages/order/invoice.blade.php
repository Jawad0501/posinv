
@extends('layouts.app')

@section('title', 'Invoice No '. $order->invoice)

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
                    <div class="fw-semibold fs-6 text-black">Invoice No. {{$order->invoice}}</div>
                    <div class="fw-semibold" style="font-size: 14px;">Customer Name: {{$order->user->full_name}}</div>
                    <div class="fw-semibold" style="font-size: 14px;">Customer Phone: {{$order->user->phone}}</div>
                    <div class="fw-semibold" style="font-size: 14px;">Date: {{ date('d F Y', strtotime($order->created_at))}}</div>
                </div>
                <div class="text-end">
                    <span class="text-uppercase fw-medium text-black" style="font-size: 50px; letter-spacing: 3px;">Invoice</span>
                </div>
            </div>

            <div style="max-width: 800px; width: 100%; margin: auto">
                <div class="">
                    <!-- <div>
                        <div style="background-color:#9E9E9E; width: 274px; padding: 5px 0px; text-transform: uppercase; color: white; text-align: center">
                            Invoice To
                        </div>

                        <div class=""  style="background-color:#f2f2f3; width:274px; padding-left: 95px; padding-top: 20px; padding-bottom:20px">
                            <div>
                                <div>
                                    <div style="font-size: 10px; font-weight: 600">{{$order->user?->name}}</div>
                                    <div class="text-uppercase" style="color:black; font-weight:600; font-size:">{{$order->user?->company_name}}</div>
                                </div>

                                <div style="margin-top: 20px">
                                    @if($order->user?->delivery_address != null)
                                        <div style="font-size: 10px; font-weight: 500">{{$order->user?->delivery_address->address_line_1}}, {{$order->user?->delivery_address->address_line_2}}</div>
                                        <div style="font-size: 10px; font-weight: 500">{{$order->user?->delivery_address->city}}</div>
                                        <div style="font-size: 10px; font-weight: 500">{{$order->user?->delivery_address->state}}</div>
                                        <div style="font-size: 10px; font-weight: 500">{{$order->user?->delivery_address->country}}</div>
                                    @endif
                                </div>

                                <div style="margin-top: 20px">
                                    <div style="font-size: 10px; font-weight: 600; color: black; line-height: 2">PO Number</div>
                                    @if($order->user?->delivery_address != null)
                                        <div style="font-size: 10px; font-weight: 500">{{$order->user?->delivery_address->postcode}}</div>
                                    @endif
                                </div>

                                <div style="margin-top: 20px">
                                    <div class="text-uppercase" style="font-size: 10px; font-weight: 600; color: black; line-height: 2">Bank Details</div>
                                    <div style="font-size: 10px; font-weight: 500">Name: Immersive Brands Ltd</div>
                                    <div style="font-size: 10px; font-weight: 500">Bank Name: Metro Bank</div>
                                    <div style="font-size: 10px; font-weight: 500">Account Number: 47870479</div>
                                    <div style="font-size: 10px; font-weight: 500">Sort Code: 23-05-80</div>
                                    <div style="font-size: 10px; font-weight: 500; margin-top: 20px">VAT: 434 2299 96</div>
                                    <div style="font-size: 10px; font-weight: 500; margin-top: 20px">@if($order->account_type != null)Terms: {{$order->account_type}}@endif</div>
                                </div>
                            </div>

                            <div>
                                <div style="margin-top: 150px">
                                    <div class="text-uppercase" style="font-size: 10px; font-weight: 600; color: black; line-height: 2">Contact</div>
                                    <div style="font-size: 10px; font-weight: 500; margin-top: 10px">020 3005 3217</div>
                                    <div style="font-size: 10px; font-weight: 500; margin-top: 5px">info@immersivebrands.co.uk</div>
                                </div>

                                <div style="border-top: 6px solid gray; width: 30px; margin: 30px 0px;">

                                </div>


                                <div class="text-uppercase" style="font-size: 16px; font-weight: 600; color: black">
                                    This is not <br> a tax invoice
                                </div>

                                <div style="margin-top: 40px">
                                    <div style="font-size: 10px; font-weight: 500; color: black">
                                        Company Registration No.
                                    </div>
                                    <div style="font-size: 10px; font-weight: 500; color: black">
                                        14617141.
                                    </div>
                                </div>

                                <div style="margin-top: 20px">
                                    <div style="font-size: 10px; font-weight: 500; color: black">Regitered Office</div>
                                    <div style="font-size: 10px; font-weight: 500; color: black">14th Floor, 25 Cabot Square,</div>
                                    <div style="font-size: 10px; font-weight: 500; color: black">London, London, E14 4QZ,</div>
                                    <div style="font-size: 10px; font-weight: 500; color: black">United Kingdom.</div>
                                </div>
                            </div>
                        </div>
                    </div> -->
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
                                        $subtotal = $order->orderDetails->sum('total_price');
                                    @endphp

                                    @foreach($order->orderDetails as $item)
                                        <tr>
                                            <td style="width: 50px; text-align:center; font-size: 12px; font-weight: 600; color: black">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td style="width: 270px; border-bottom: 2px solid #f2f2f3">
                                                <div style="font-size: 12px; font-weight: 600; color: black; margin-top: 10px">{{$item->ingredient?->name ? $item->ingredient?->name : ''}}</div>
                                            </td>
                                            <td style="width: 116px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{$item->quantity}}
                                            </td>
                                            <td style="width: 118px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{$item->price}}
                                            </td>
                                            <td style="width: 118px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{$item->total_price}}
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
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 5px">{{convert_amount($item->order->payment->give_amount)}}</div>
                        </div>
                        <div class="d-flex justify-content-between" style="width:185px; margin-left:auto; border-bottom:  2px solid #f2f2f3; padding: 10px 0px">
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase;">Due</div>
                            <div style="color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 5px">{{convert_amount($item->order->payment->due_amount)}}</div>
                        </div>
                        </div>
                        

                        <div style="text-transform: uppercase; text-align:end; font-size: 25px; font-weight: 600; color: black">
                            <div>Thank you</div>
                            <div>For your business</div>
                        </div>
                    </div>
                </div>
            </div>
            <x-card-heading-button :url="route('pos.order.download-invoice', $order->id)" title="Download" icon="download" />

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
