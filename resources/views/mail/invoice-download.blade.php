<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="http://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">p -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet"> --}}
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        .container {
            padding: 40px 20px;
        }

        .w-50 {
            width: 50%;
        }

        .w-100 {
            width: 100%;
        }

        .text-start {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-center {
            justify-content: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .fw-medium {
            font-weight: 500;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .fs-5{
            font-size: 1.15rem;
        }

        .border-none {
            border: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: normal;

        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .btn-place {
            padding: 8px 16px;
            background: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn-place:hover {
            color: #fff;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .page-break {
            page-break-after: always;
        }

        .page-break:last-child {
            page-break-after: auto !important;
        }
    </style>
</head>

<body style="color: #495057; max-width: 800px; width: 100%; margin: 0 auto;">

    @php
        $page_break = 1;
        $items = $order->orderDetails;
        $filteredItems = [];
        $last_item = false;
        $subtotal = 0;
        $total = 0;
        $count = 1;
        foreach($items as $item){
            $subtotal = $subtotal + (($item->price * $item->quantity));
            $total = $total + $item->grand_total;
            $item['no'] = $count;
            $count = $count + 1;
        }
    @endphp

@for($i=0; $i<$page_break; $i++)
    @if($i==1 && $page_break==1)
     @break
    @endif
    <div class="container page-break" style="height: 1000px">
        
        <div style="position:relative; height: 100px; width: 100%">
            <div class="w-100" style="position:absolute">

                <!-- <img src="https://1.bp.blogspot.com/-JdbM280Rs2Y/XjxgQTF6HEI/AAAAAAAAAAg/YdyLp9V1tDkp2-kaFXCcgc9IeQNxt5o1gCLcBGAsYHQ/s1600/dummy.jpg" width="100px" alt=""> -->
                <img src="https://shmector.com/_ph/13/188552034.png" width="100px" alt="">

            </div>
            <div style="position: absolute; right: 0; text-align:right">
                <div style="width:320px; font-weight: 600; font-size: 24px; color: black">{{setting('app_title')}}</div>
                <div  style="font-size: 20px;  color: gray; font-weight: 600">{{setting('address')}}</div>
            </div>
        </div>

        <div  style="position:relative; width: 100%; height: 80px;">
            <div style="position:absolute">
                <div class="fw-semibold fs-6 text-black">Invoice No. {{$order->invoice}}</div>
                <div class="fw-semibold" style="font-size: 14px;">Customer Name: {{$order->user->full_name}}</div>
                <div class="fw-semibold" style="font-size: 14px;">Customer Phone: {{$order->user->phone}}</div>
                <div class="fw-semibold" style="font-size: 14px;">Date: {{ date('d F Y', strtotime($order->created_at))}}</div>
            </div>
            <div style="position:absolute; right:0; text-align: right;">
                <span class="text-uppercase fw-medium text-black" style="font-size: 30px; letter-spacing: 2px;">Invoice</span>
            </div>
        </div>

        <div style="width:100%">
            <div style="">
                <div>
                    <table  style="border-spacing: 0px !important; width:100%">
                        <thead style="background-color: #9E9E9E; ">
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">No.</td>
                            <td  style=" padding: 5px 0px; text-transform: uppercase; color: white;">Description</td>
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">QTY</td>
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Unit Price</td>
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Total Price</td>
                        </thead>

                        <tbody>
                            {{-- @php
                                $subtotal = 0;
                                $total = 0;
                                $count = 1;
                                foreach($items as $item){
                                    $subtotal = $subtotal + $item->subtotal;
                                    $total = $total + $item->total;
                                    $item['no'] = $count;
                                    $count = $count + 1;
                                }
                            @endphp --}}
                            @php
                                $temp_item = null;
                            @endphp
                            @foreach($items as $item)
                            @if($item->no/(11*($i+1)) == 1)
                                @php
                                $page_break = $page_break + 1;
                                @endphp
                                @foreach($items as $key => $remove_item)
                                    @if ($remove_item->no >= $item->no)
                                        @php
                                            // unset($items[$key])
                                            $filteredItems[$key] = $remove_item;
                                        @endphp
                                        {{-- $filteredItems->$key = $item; --}}
                                    @endif
                                @endforeach
                                @break
                            @else
                                @php
                                    $temp_item = $item
                                @endphp
                            @endif
                                <tr>
                                    <td style="width: 50px; text-align:center; font-size: 12px; font-weight: 600; color: black">
                                        {{$item->no}}
                                    </td>
                                    <td style="width: 200px; border-bottom: 2px solid #f2f2f3; padding-bottom: 20px;">
                                        <div style="font-size: 12px; font-weight: 600; color: black; margin-top: 10px">{{$item->ingredient?->name ? $item->ingredient?->name : ''}}</div>
                                    </td>
                                    <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                        {{ $item->quantity }}
                                    </td>
                                    <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                        {{ $item->price }}
                                    </td>
                                    <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                        {{ $item->total_price }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($temp_item->no == count($order->orderDetails))
                <div style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px;">
                    <div style="position: absolute; left:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-left: 13px;  margin-top: 5px;">Sub Total</div>
                    <div style="position: absolute; right:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 30px;  margin-top: 5px;">{{convert_amount($subtotal)}}</div>
                </div>
                <div style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px; border-bottom:  2px solid #f2f2f3; padding: 5px 0px">
                    <div style="position: absolute; left:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-left: 13px; margin-top: 10px;">Paid Amount</div>
                    <div style="position: absolute; right:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 30px;margin-top: 10px; ">{{convert_amount($order->payment->give_amount)}}</div>
                </div>
                <div style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px; border-bottom:  2px solid #f2f2f3; padding: 5px 0px">
                    <div style="position: absolute; left:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-left: 13px; margin-top: 10px;">Due Amount</div>
                    <div style="position: absolute; right:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 30px; margin-top: 10px;">{{convert_amount($order->payment->due_amount)}}</div>
                </div>
                <!-- <div  style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px; border-bottom:  2px solid #f2f2f3; padding: 5px 0px">

                </div> -->
                @endif


            </div>
        </div>

        <div >
            <!-- <div  style="position: relative; width: 100%; margin: auto; margin-bottom: 40px;">
                <div class="w-100" style="position:absolute; left: 0; width: 50%">

                    <img src="https://1.bp.blogspot.com/-JdbM280Rs2Y/XjxgQTF6HEI/AAAAAAAAAAg/YdyLp9V1tDkp2-kaFXCcgc9IeQNxt5o1gCLcBGAsYHQ/s1600/dummy.jpg" width="200px" alt="">

                </div>
                <div style="text-align:left; position:absolute; right: 0; width:50%">
                    <div style="width:320px; font-weight: 600; font-size: 16px; color: black">{{setting('app_title')}}</div>
                    <div  style="font-size: 12px;  color: gray; font-weight: 600">{{setting('address')}}</div>
                </div>
            </div> -->

            <!-- <div  style="position:relative; width: 100%; max-width: 800px">
                <div style="margin-top: 80px">
                    <div class="fw-semibold fs-4 text-black">No. {{$order->invoice}}</div>
                    <div class="fw-semibold" style="font-size: 12px;">Date: {{ date('d F Y', strtotime($order->created_at))}}</div>
                </div>
                <div style=" text-align: right; margin-top: 80px">
                    <span class="text-uppercase fw-medium text-black" style="font-size: 30px; letter-spacing: 2px;">Invoice</span>
                </div>
            </div> -->

            <div style="max-width: 800px; width: 100%; margin: auto; height: 100%;">
                <div style="position: relative;">
                    <!-- <div style="position: absolute; left:0; top:20%">
                        <div style="background-color:#9E9E9E; width: 207px; padding: 5px 0px; text-transform: uppercase; color: white; text-align: center;">
                            Invoice To
                        </div>

                        <div class=""  style="background-color:#f2f2f3; width:130px; padding-left: 57px; padding-right:20px; padding-top: 20px; padding-bottom:20px">
                            <div>
                                <div>
                                    <div style="font-size: 9px; font-weight: 600;  color: gray;">{{$order->user?->name}}</div>
                                    <div class="text-uppercase" style="color:black; font-weight:600; font-size:10px">{{$order->user?->company_name}}</div>
                                </div>

                                <div style="margin-top: 10px;">
                                    @if($order->user?->delivery_address != null)
                                        <div style="font-size: 9px; font-weight: 500">{{$order->user?->delivery_address->address_line_1}}, {{$order->user?->delivery_address->address_line_2}}</div>
                                        <div style="font-size: 9px; font-weight: 500">{{$order->user?->delivery_address->city}}</div>
                                        <div style="font-size: 9px; font-weight: 500">{{$order->user?->delivery_address->state}}</div>
                                        <div style="font-size: 9px; font-weight: 500">{{$order->user?->delivery_address->country}}</div>
                                    @endif
                                </div>

                                <div style="margin-top: 10px;">
                                    <div style="font-size: 9px; font-weight: 600; color: black; line-height: 2">PO Number</div>
                                    @if($order->order_number != null)
                                        <div style="font-size: 9px; font-weight: 500">{{$order->order_number}}</div>
                                    @endif
                                </div>

                                <div style="margin-top: 10px;">
                                    <div class="text-uppercase" style="font-size: 9px; font-weight: 600; color: black; line-height: 2">Bank Details</div>
                                    <div style="font-size: 9px; font-weight: 500">Name: Immersive Brands Ltd</div>
                                    <div style="font-size: 9px; font-weight: 500">Bank Name: Metro Bank</div>
                                    <div style="font-size: 9px; font-weight: 500">Account Number: 47870479</div>
                                    <div style="font-size: 9px; font-weight: 500">Sort Code: 23-05-80</div>
                                    <div style="font-size: 9px; font-weight: 500; margin-top: 10px">VAT: 434 2299 96</div>
                                    <div style="font-size: 9px; font-weight: 500; margin-top: 10px; margin-bottom: 10px;">Terms: {{$order->account_type}}</div>
                                </div>

                                {{-- <div style="margin-top: 10px;">
                                    <div style="font-size: 9px; font-weight: 600; text-transform: uppercase">This invoice is valid for 28 days</div>
                                </div> --}}
                            </div>

                            <div>
                                <div style="margin-top: 80px;">
                                    <div class="text-uppercase" style="font-size: 10px; font-weight: 600; color: black;">Contact</div>
                                    <div style="font-size: 9px; font-weight: 500; margin-top: 5px">020 3005 3217</div>
                                    <div style="font-size: 9px; font-weight: 500; margin-top: 5px; margin-bottom: 10px;">info@immersivebrands.co.uk</div>
                                </div>

                                <div style="border-top: 6px solid gray; width: 30px; margin-top: 20px; margin-bottom: 10px">

                                </div>


                                <div style="">
                                    <div style="font-size: 9px; font-weight: 500; color: black">
                                        Company Registration No.
                                    </div>
                                    <div style="font-size: 9px; font-weight: 500; color: black">
                                        14617141.
                                    </div>
                                </div>

                                <div style="">
                                    <div style="font-size: 9px; font-weight: 500; color: black; margin-top: 10px;">Regitered Office</div>
                                    <div style="font-size: 9px; font-weight: 500; color: black">14th Floor, 25 Cabot Square,</div>
                                    <div style="font-size: 9px; font-weight: 500; color: black">London, London, E14 4QZ,</div>
                                    <div style="font-size: 9px; font-weight: 500; color: black">United Kingdom.</div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div style="position: absolute; right:0; left: 27%; top:20%;">
                        <div>
                            <table  style="border-spacing: 0px !important;">
                                <thead style="background-color: #9E9E9E; ">
                                    <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">No.</td>
                                    <td  style=" padding: 5px 0px; text-transform: uppercase; color: white;">Description</td>
                                    <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">QTY</td>
                                    <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Unit</td>
                                    <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Price</td>
                                </thead>

                                <tbody>
                                    {{-- @php
                                        $subtotal = 0;
                                        $total = 0;
                                        $count = 1;
                                        foreach($items as $item){
                                            $subtotal = $subtotal + $item->subtotal;
                                            $total = $total + $item->total;
                                            $item['no'] = $count;
                                            $count = $count + 1;
                                        }
                                    @endphp --}}
                                    @php
                                        $temp_item = null;
                                    @endphp
                                    @foreach($items as $item)
                                    @if($item->no/(11*($i+1)) == 1)
                                        @php
                                        $page_break = $page_break + 1;
                                        @endphp
                                        @foreach($items as $key => $remove_item)
                                            @if ($remove_item->no >= $item->no)
                                                @php
                                                    // unset($items[$key])
                                                    $filteredItems[$key] = $remove_item;
                                                @endphp
                                                {{-- $filteredItems->$key = $item; --}}
                                            @endif
                                        @endforeach
                                        @break
                                    @else
                                        @php
                                            $temp_item = $item
                                        @endphp
                                    @endif
                                        <tr>
                                            <td style="width: 50px; text-align:center; font-size: 12px; font-weight: 600; color: black">
                                                {{$item->no}}
                                            </td>
                                            <td style="width: 200px; border-bottom: 2px solid #f2f2f3; padding-bottom: 20px;">
                                                <div style="font-size: 12px; font-weight: 600; color: black; margin-top: 10px">{{$item->product?->name ? $item->product?->name : $item->product_name}}</div>
                                                {{-- <div style="font-size: 10px; font-weight: 500; margin-top: 10px">{{$item->product?->description ? $item->product?->description : $item->product_description}}</div> --}}
                                            </td>
                                            <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{ $item->quantity }}
                                            </td>
                                            <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{ $item->unit_price }}
                                            </td>
                                            <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                                {{ $item->subtotal }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($temp_item->no == count($order->orderDetails))
                        <div style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px;">
                            <div style="position: absolute; left:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-left: 13px;  margin-top: 5px;">Sub Total</div>
                            <div style="position: absolute; right:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 30px;  margin-top: 5px;">{{convert_amount($subtotal)}}</div>
                        </div>
                        <div style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px; border-bottom:  2px solid #f2f2f3; padding: 5px 0px">
                            <div style="position: absolute; left:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-left: 13px; margin-top: 10px;">Discount</div>
                            <div style="position: absolute; right:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 30px;margin-top: 10px; ">{{convert_amount($order->total_discount)}}</div>
                        </div>
                        <div style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px; border-bottom:  2px solid #f2f2f3; padding: 5px 0px">
                            <div style="position: absolute; left:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-left: 13px; margin-top: 10px;">Shipping</div>
                            <div style="position: absolute; right:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 30px; margin-top: 10px;">{{convert_amount($order->shipping_amount)}}</div>
                        </div>
                        <div style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px; border-bottom:  2px solid #f2f2f3; padding: 5px 0px">
                            <div style="position: absolute; left:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-left: 13px; margin-top: 10px;">Grand Total</div>
                            <div style="position: absolute; right:0; color: black; font-weight: 600; font-size: 11px; text-transform: uppercase; padding-right: 30px; margin-top: 10px;">{{convert_amount(($total + $order->shipping_amount))}}</div>
                        </div>
                        <div  style="position: relative; width:270px; margin-left:auto; margin-bottom: 20px; border-bottom:  2px solid #f2f2f3; padding: 5px 0px">

                        </div>
                        @endif


                    </div> -->

                </div>
            </div>
        </div>

        @php
            $items = (object) $filteredItems;
        @endphp
        </div>
    @endfor
</body>

</html>

